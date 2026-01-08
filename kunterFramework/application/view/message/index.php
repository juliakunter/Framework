<!-- Äußerer Container für die gesamte Seite -->
<div class="container">

    <!-- Überschrift der Seite / des Controllers -->
    <h1>MessageController/index</h1>

    <!-- Box für den Hauptinhalt -->
    <div class="box">

        <!-- Gibt System-Feedback aus (Fehler- oder Erfolgsmeldungen) -->
        <?php $this->renderFeedbackMessages(); ?>

        <!-- Überschrift für die Unterhaltungsübersicht -->
        <h3>Ihre Unterhaltungen</h3>

        <!-- Prüft, ob Unterhaltungen vorhanden sind -->
        <?php if ($this->conversations) { ?>

            <!-- Tabelle zur Anzeige aller Unterhaltungen -->
            <table class="message-table">

                <!-- Tabellenkopf -->
                <thead>
                    <tr>
                        <!-- Spalte für die Partner-ID -->
                        <td>Partner ID</td>

                        <!-- Spalte für den Zeitpunkt der letzten Nachricht -->
                        <td>Letzte Nachricht</td>

                        <!-- Spalte für ungelesene Nachrichten -->
                        <td>Ungelesen</td>
                    </tr>
                </thead>

                <!-- Tabellenkörper -->
                <tbody>

                    <!-- Schleife über alle Unterhaltungen -->
                    <?php foreach ($this->conversations as $chat): ?>

                        <!-- Tabellenzeile für eine einzelne Unterhaltung -->
                        <tr>

                            <!-- Anzeige der ID des Chat-Partners -->
                            <td>User #<?= $chat->partner_id ?></td>

                            <!-- Anzeige des Zeitstempels der letzten Nachricht -->
                            <td><?= $chat->last_time ?></td>

                            <!-- Spalte für ungelesene Nachrichten -->
                            <td>

                                <!-- Prüft, ob ungelesene Nachrichten vorhanden sind -->
                                <?php if ($chat->unread > 0): ?>

                                    <!-- Anzeige der Anzahl ungelesener Nachrichten als Badge -->
                                    <span class="badge red"><?= $chat->unread ?></span>

                                <!-- Falls keine ungelesenen Nachrichten vorhanden sind -->
                                <?php else: ?>
                                    0
                                <?php endif; ?>

                            </td>

                            <!-- Link zum Öffnen des Chats mit dem jeweiligen Partner -->
                            <td><a href="<?= Config::get('URL') . 'message/show/' . $chat->partner_id ?>">Chat öffnen</a></td>

                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>

        <!-- Falls keine Unterhaltungen vorhanden sind -->
        <?php } else { ?>

            <!-- Hinweistext, wenn noch keine Unterhaltungen existieren -->
            <div>Noch keine Unterhaltungen vorhanden.</div>

        <?php } ?>

    </div>
</div>
