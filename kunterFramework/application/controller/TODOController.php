<?php

/**
 * TodoController
 * Simple controller for Todo list (add / delete / toggle done)
 */
class TodoController extends Controller
{
    /**
     * Only logged-in users are allowed
     */
    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();
    }

    /**
     * Show all todos
     * URL: /todo/index
     */
    public function index()
    {
        $this->View->render('todo/index', [
            'todos' => TodoModel::getAllTodos()
        ]);
    }

    /**
     * Create a new todo
     * POST: todo_text
     * URL: /todo/create
     */
    public function create()
    {
        TodoModel::createTodo(Request::post('todo_text'));
        Redirect::to('todo');
    }

    /**
     * Toggle todo (done / undone)
     * URL: /todo/toggle/ID
     */
    public function toggle($todo_id)
    {
        TodoModel::toggleTodo($todo_id);
        Redirect::to('todo');
    }

    /**
     * Delete a todo
     * URL: /todo/delete/ID
     */
    public function delete($todo_id)
    {
        TodoModel::deleteTodo($todo_id);
        Redirect::to('todo');
    }
}
