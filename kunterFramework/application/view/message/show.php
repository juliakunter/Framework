<!-- Äußerer Container für die gesamte Chat-Seite -->
<div class="container">

    <!-- Überschrift des Chats, ergänzt um die Partner-ID -->
    <h1>Chat zwieschen Lukas und Julia<?= $this->partnerId ?></h1>

    <!-- Box-Container für den Chat-Inhalt -->
    <div class="box">

        <!-- Feedback-Ausgabe für Fehler- oder Erfolgsmeldungen -->
        <?php $this->renderFeedbackMessages(); ?>

        <!-- Chat-Fenster mit fixer Höhe, Scrollbar und Rahmen -->
        <div class="chat-window" style="max-height:400px; overflow-y:auto; border:1px solid #ccc; padding:10px;">

            <!-- Prüft, ob Nachrichten vorhanden sind -->
            <?php if (!empty($this->messages)) : ?>

                <!-- Schleife durch alle Nachrichten -->
                <?php foreach ($this->messages as $msg) : ?>

                    <!-- Container für eine einzelne Nachricht -->
                     <div style="
                        padding:8px; margin:5px; border-radius:10px; max-width:70%;
                        <?= $msg->sender_id == Session::get('user_id') 
                            ? 'background:#DCF8C6;margin-left:auto;text-align:right;' 
                            : 'background:#FFF;margin-right:auto;text-align:left;border:1px solid #ddd;' ?>
                    ">

                        <!-- Gibt den Nachrichtentext HTML-sicher aus -->
                        <?= htmlentities($msg->message_text) ?>

                        <!-- Zeigt den Zeitstempel der Nachricht klein und grau an -->
                        <div style="font-size:10px; color:#999;"><?= $msg->timestamp ?></div>
                    </div>

                <!-- Ende der Nachrichten-Schleife -->
                <?php endforeach; ?>

            <!-- Falls keine Nachrichten vorhanden sind -->
            <?php else: ?>
                <p>Keine Nachrichten vorhanden.</p>
            <?php endif; ?>

        <!-- Ende des Chat-Fensters -->
        </div>

        <!-- Formular zum Senden einer neuen Nachricht -->
        <form method="post" action="<?= Config::get('URL') ?>message/send" style="margin-top:10px;">

            <!-- Verstecktes Feld für die Empfänger-ID -->
            <input type="hidden" name="receiver_id" value="<?= $this->partnerId ?>">

            <!-- Eingabefeld für den Nachrichtentext -->
            <input type="text" name="message_text" placeholder="Schreibe eine Nachricht..." style="width:80%;" required>

            <!-- Button zum Absenden der Nachricht -->
            <button type="submit">Senden</button>
        </form>

    </div>
</div>
