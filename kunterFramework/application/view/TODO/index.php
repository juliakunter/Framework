<style>
.todo-container {
    max-width: 500px;
    margin: 40px auto;
    background: white;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    font-family: Arial, sans-serif;
}

.add-todo {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.add-todo input {
    flex: 1;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.add-todo button {
    background: #4f46e5;
    border: none;
    color: white;
    padding: 0 18px;
    border-radius: 8px;
    font-size: 20px;
    cursor: pointer;
}

.todo-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.todo-list li {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f9fafb;
    border-radius: 8px;
    margin-bottom: 8px;
}

.todo-list li.done input {
    text-decoration: line-through;
    color: #777;
}

.circle {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid #bbb;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.circle.done {
    background: #22c55e;
    border-color: #22c55e;
}

.todo-text {
    flex: 1;
}

.todo-text input {
    width: 100%;
    border: none;
    background: transparent;
    font-size: 15px;
}

.todo-text input:focus {
    outline: none;
    background: white;
    border-radius: 6px;
    padding: 4px;
}

.delete {
    color: #ef4444;
    text-decoration: none;
    font-size: 16px;
}
</style>

<div class="todo-container">
    <h2>📝 My Todos</h2>

    <form method="post" action="<?= Config::get('URL'); ?>todo/create" class="add-todo">
        <input type="text" name="todo_text" placeholder="New todo..." required>
        <button>＋</button>
    </form>

    <ul class="todo-list">
        <?php foreach ($this->todos as $todo): ?>
            <li class="<?= $todo->is_done ? 'done' : ''; ?>">

                <!-- Kreis -->
                <a class="circle <?= $todo->is_done ? 'done' : ''; ?>"
                   href="<?= Config::get('URL'); ?>todo/toggle/<?= $todo->todo_id; ?>">
                </a>

                <!-- Editierbarer Text -->
                <form class="todo-text"
                      method="post"
                      action="<?= Config::get('URL'); ?>todo/update/<?= $todo->todo_id; ?>">
                    <input type="text"
                           name="todo_text"
                           value="<?= htmlentities($todo->todo_text); ?>"
                           onblur="this.form.submit()">
                </form>

                <!-- Delete -->
                <a class="delete"
                   href="<?= Config::get('URL'); ?>todo/delete/<?= $todo->todo_id; ?>">
                    ✖
                </a>

            </li>
        <?php endforeach; ?>
    </ul>
</div>
