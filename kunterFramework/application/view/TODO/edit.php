<div class="container">
    <h1>My Todo List</h1>

    <div class="box">

        <!-- Feedback Messages -->
        <?php $this->renderFeedbackMessages(); ?>

        <!-- Add Todo -->
        <form method="post" action="<?php echo Config::get('URL'); ?>todo/create">
            <input type="text" name="todo_text" placeholder="New todo..." required />
            <input type="submit" value="Add Todo" />
        </form>

        <hr>

        <!-- Todo List -->
        <?php if (!empty($this->todos)) { ?>
            <ul>
                <?php foreach ($this->todos as $todo) { ?>
                    <li>
                        <!-- Toggle -->
                        <a href="<?php echo Config::get('URL'); ?>todo/toggle/<?php echo $todo->todo_id; ?>">
                            <?php echo $todo->is_done ? '✔' : '⬜'; ?>
                        </a>

                        <!-- Text -->
                        <span style="<?php echo $todo->is_done ? 'text-decoration: line-through;' : ''; ?>">
                            <?php echo htmlentities($todo->todo_text); ?>
                        </span>

                        <!-- Delete -->
                        <a href="<?php echo Config::get('URL'); ?>todo/delete/<?php echo $todo->todo_id; ?>"
                           style="color:red; margin-left:10px;">
                            ✖
                        </a>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No todos yet.</p>
        <?php } ?>

    </div>
</div>
