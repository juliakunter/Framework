<div class="container">
    <h1>Chat mit User #<?= htmlentities($this->partnerId); ?></h1>

    <div class="box">
        <!-- Feedback -->
        <?php $this->renderFeedbackMessages(); ?>

        <!-- Nachrichtenverlauf -->
        <div class="chat-box" style="border:1px solid #ccc; padding:10px; height:300px; overflow-y:auto;">
            <?php if (!empty($this->messages)) { ?>
                <?php foreach ($this->messages as $msg) { ?>
                    <div style="margin-bottom:10px; <?= $msg->sender_id == Session::get('user_id') ? 'text-align:right;' : ''; ?>">
                        <div style="display:inline-block; background:<?= $msg->sender_id == Session::get('user_id') ? '#dcf8c6' : '#f1f0f0'; ?>; padding:6px 10px; border-radius:10px;">
                            <strong><?= $msg->sender_id == Session::get('user_id') ? 'Du' : 'User '.$msg->sender_id; ?>:</strong><br>
                            <?= nl2br(htmlentities($msg->message_text)); ?><br>
                            <small><?= htmlentities($msg->timestamp); ?></small>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>Noch keine Nachrichten in diesem Chat.</p>
            <?php } ?>
        </div>

        <!-- Formular zum Senden -->
        <form method="post" action="<?= Config::get('URL'); ?>message/send" style="margin-top:15px;">
            <input type="hidden" name="receiver_id" value="<?= htmlentities($this->partnerId); ?>">
            <textarea name="message_text" rows="3" placeholder="Nachricht schreiben..." required style="width:100%;"></textarea><br>
            <input type="submit" value="Senden" />
        </form>
    </div>
</div>