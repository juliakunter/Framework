<?php

/**
 * TodoModel
 * Simple CRUD for Todo list (add / delete / toggle done)
 */
class TodoModel
{
    /**
     * Get all todos of the logged-in user
     */
    public static function getAllTodos()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT todo_id, todo_text, is_done 
                FROM todos 
                WHERE user_id = :user_id";

        $query = $database->prepare($sql);
        $query->execute([
            ':user_id' => Session::get('user_id')
        ]);

        return $query->fetchAll();
    }

    /**
     * Create a new todo
     */
    public static function createTodo($todo_text)
    {
        if (!$todo_text || strlen($todo_text) === 0) {
            Session::add('feedback_negative', 'Todo darf nicht leer sein');
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO todos (todo_text, user_id) 
                VALUES (:todo_text, :user_id)";

        $query = $database->prepare($sql);
        $query->execute([
            ':todo_text' => $todo_text,
            ':user_id'   => Session::get('user_id')
        ]);

        return $query->rowCount() === 1;
    }

    /**
     * Toggle todo (done / undone)
     */
    public static function toggleTodo($todo_id)
    {
        if (!$todo_id) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE todos 
                SET is_done = NOT is_done 
                WHERE todo_id = :todo_id 
                AND user_id = :user_id 
                LIMIT 1";

        $query = $database->prepare($sql);
        $query->execute([
            ':todo_id' => $todo_id,
            ':user_id' => Session::get('user_id')
        ]);

        return $query->rowCount() === 1;
    }

    /**
     * Delete a todo
     */
    public static function deleteTodo($todo_id)
    {
        if (!$todo_id) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM todos 
                WHERE todo_id = :todo_id 
                AND user_id = :user_id 
                LIMIT 1";

        $query = $database->prepare($sql);
        $query->execute([
            ':todo_id' => $todo_id,
            ':user_id' => Session::get('user_id')
        ]);

        return $query->rowCount() === 1;
    }
}
