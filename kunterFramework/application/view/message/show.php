<div class="container">
    <h1>Chat mit User #<?= $this->partnerId ?></h1>

    <div class="box">
        <!-- Feedback (Fehler oder Erfolg) -->
        <?php $this->renderFeedbackMessages(); ?>

        <div class="chat-window" style="max-height:400px; overflow-y:auto; border:1px solid #ccc; padding:10px;">
            <?php if (!empty($this->messages)) : ?>
                <?php foreach ($this->messages as $msg) : ?>
                    <div style="
                        padding:8px; margin:5px; border-radius:10px; max-width:70%;
                        <?= $msg->sender_id == Session::get('user_id') 
                            ? 'background:#DCF8C6;margin-left:auto;text-align:right;' 
                            : 'background:#FFF;margin-right:auto;text-align:left;border:1px solid #ddd;' ?>
                    ">
                        <?= htmlentities($msg->message_text) ?>
                        <div style="font-size:10px; color:#999;"><?= $msg->timestamp ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Keine Nachrichten vorhanden.</p>
            <?php endif; ?>
        </div>

        <!-- Nachricht senden -->
        <form method="post" action="<?= Config::get('URL') ?>message/send" style="margin-top:10px;">
            <input type="hidden" name="receiver_id" value="<?= $this->partnerId ?>">
            <input type="text" name="message_text" placeholder="Schreibe eine Nachricht..." style="width:80%;" required>
            <button type="submit">Senden</button>
        </form>
    </div>
</div>