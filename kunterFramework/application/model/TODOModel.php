<?php

class TodoModel
{
    public static function getAllTodos()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT todo_id, todo_text, is_done
                FROM todos
                WHERE user_id = :user_id";

        $query = $db->prepare($sql);
        $query->execute([
            ':user_id' => Session::get('user_id')
        ]);

        return $query->fetchAll();
    }

    public static function createTodo($todo_text)
    {
        if (!$todo_text) return false;

        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO todos (todo_text, user_id)
                VALUES (:text, :user)";

        $query = $db->prepare($sql);
        $query->execute([
            ':text' => $todo_text,
            ':user' => Session::get('user_id')
        ]);
    }

    public static function toggleTodo($todo_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE todos
                SET is_done = NOT is_done
                WHERE todo_id = :id AND user_id = :user";

        $query = $db->prepare($sql);
        $query->execute([
            ':id'   => $todo_id,
            ':user'=> Session::get('user_id')
        ]);
    }

    public static function updateTodo($todo_id, $todo_text)
    {
        if (!$todo_text) return false;

        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE todos
                SET todo_text = :text
                WHERE todo_id = :id AND user_id = :user";

        $query = $db->prepare($sql);
        $query->execute([
            ':text' => $todo_text,
            ':id'   => $todo_id,
            ':user' => Session::get('user_id')
        ]);
    }

    public static function deleteTodo($todo_id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM todos
                WHERE todo_id = :id AND user_id = :user";

        $query = $db->prepare($sql);
        $query->execute([
            ':id'   => $todo_id,
            ':user' => Session::get('user_id')
        ]);
    }
}
