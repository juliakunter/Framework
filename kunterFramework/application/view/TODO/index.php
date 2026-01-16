<div class="container">
    <h1>My Todo List</h1>

    <div class="box">

        <!-- Feedback Messages -->
        <?php $this->renderFeedbackMessages(); ?>

        <!-- Create Todo -->
        <form method="post" action="<?php echo Config::get('URL'); ?>todo/create">
            <label>New todo: </label>
            <input type="text" name="todo_text" required />
            <input type="submit" value="Add Todo" autocomplete="off" />
        </form>

        <hr>

        <?php if (!empty($this->todos)) { ?>
            <table class="note-table">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Status</td>
                        <td>Todo</td>
                        <td>Delete</td>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($this->todos as $todo) { ?>
                    <tr>
                        <td><?= $todo->todo_id; ?></td>

                        <!-- Toggle done -->
                        <td>
                            <a href="<?= Config::get('URL') . 'todo/toggle/' . $todo->todo_id; ?>">
                                <?= $todo->is_done ? '✔' : '⬜'; ?>
                            </a>
                        </td>

                        <!-- Todo text -->
                        <td style="<?= $todo->is_done ? 'text-decoration: line-through;' : ''; ?>">
                            <?= htmlentities($todo->todo_text); ?>
                        </td>

                        <!-- Delete -->
                        <td>
                            <a href="<?= Config::get('URL') . 'todo/delete/' . $todo->todo_id; ?>">
                                
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div>No todos yet. Add one!</div>
        <?php } ?>

    </div>
</div>
