<div class="container">
    <h2>Meine Nachrichten</h2>

    <?php if (!empty($this->messages)) : ?>
        <?php foreach ($this->messages as $msg) : ?>
            <div style="
                border:1px solid #ccc;
                border-radius:8px;
                padding:10px;
                margin-bottom:10px;
                max-width:600px;
                background: <?= $msg->gelesen ? '#f9f9f9' : '#e6f0ff'; ?>
            ">
                <strong><?= htmlspecialchars($msg->sender_name); ?></strong><br>

                <?= htmlspecialchars(mb_strimwidth($msg->message_text, 0, 40, '...')); ?><br>

                <small><?= htmlspecialchars($msg->created_at); ?></small><br><br>

                <button onclick="openMessage(<?= (int)$msg->message_id ?>)">
                    Nachricht öffnen
                </button>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Keine Nachrichten vorhanden.</p>
    <?php endif; ?>
</div>

<!-- MODAL -->
<div id="messageModal" style="
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.5);
">
    <div style="
        background:#fff;
        padding:20px;
        max-width:500px;
        margin:100px auto;
        border-radius:10px;
    ">
        <h3>Nachricht</h3>
        <p id="modalText"></p>
        <button onclick="closeModal()">Schließen</button>
    </div>
</div>

<script>
function openMessage(id) {
    fetch('<?= Config::get("URL"); ?>message/read/' + id)
        .then(res => res.json())
        .then(data => {
            document.getElementById('modalText').innerText = data.message_text;
            document.getElementById('messageModal').style.display = 'block';
        });
}

function closeModal() {
    document.getElementById('messageModal').style.display = 'none';
    location.reload(); // Badge aktualisieren
}
</script>
