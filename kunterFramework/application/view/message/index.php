<div class="container">
    <h1>MessageController/index</h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <h3>Ihre Unterhaltungen</h3>

        <?php if ($this->conversations) { ?>
            <table class="message-table">
                <thead>
                    <tr>
                        <td>Partner ID</td>
                        <td>Letzte Nachricht</td>
                        <td>Ungelesen</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->conversations as $chat): ?>
                        <tr>
                            <td>User #<?= $chat->partner_id ?></td>
                            <td><?= $chat->last_time ?></td>
                            <td>
                                <?php if ($chat->unread > 0): ?>
                                    <span class="badge red"><?= $chat->unread ?></span>
                                <?php else: ?>
                                    0
                                <?php endif; ?>
                            </td>
                            <td><a href="<?= Config::get('URL') . 'message/show/' . $chat->partner_id ?>">Chat öffnen</a></td>
                        </tr>
                    <?php endforeach; ?>


                </tbody>
            </table>
        <?php } else { ?>
            <div>Noch keine Unterhaltungen vorhanden.</div>
        <?php } ?>

    </div>
</div>