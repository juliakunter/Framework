<?php

class TodoController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();
    }

    public function index()
    {
        $this->View->render('todo/index', [
            'todos' => TodoModel::getAllTodos()
        ]);
    }

    public function create()
    {
        TodoModel::createTodo(Request::post('todo_text'));
        Redirect::to('todo');
    }

    public function toggle($todo_id)
    {
        TodoModel::toggleTodo($todo_id);
        Redirect::to('todo');
    }

    public function update($todo_id)
    {
        TodoModel::updateTodo($todo_id, Request::post('todo_text'));
        Redirect::to('todo');
    }

    public function delete($todo_id)
    {
        TodoModel::deleteTodo($todo_id);
        Redirect::to('todo');
    }
}
