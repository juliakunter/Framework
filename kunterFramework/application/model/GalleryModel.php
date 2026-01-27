<?php

// Diese Klasse ist das "Model" im MVC-System.
// Sie ist ausschließlich dafür zuständig,
// mit der Datenbank zu sprechen und Bilder zu speichern oder zu laden.
class GalleryModel
{
    /**
     * Speichert ein neues Bild in der Datenbank.
     *
     * Diese Methode wird vom Controller aufgerufen,
     * nachdem ein Bild erfolgreich auf den Server hochgeladen wurde.
     *
     * @param int    $userId   ID des Users, dem das Bild gehört
     * @param string $fileName Dateiname des Bildes auf dem Server
     *
     * @return bool true = Bild wurde gespeichert
     *              false = Einfügen fehlgeschlagen
     */
    public static function addImage($userId, $fileName)
    {
        // ---------------------------------------------------------
        // 1) Verbindung zur Datenbank holen
        // ---------------------------------------------------------
        // DatabaseFactory kümmert sich darum,
        // dass es nur eine zentrale DB-Verbindung gibt.
        $db = DatabaseFactory::getFactory()->getConnection();

        // ---------------------------------------------------------
        // 2) SQL-Befehl vorbereiten
        // ---------------------------------------------------------
        // Wir verwenden Platzhalter (:user_id, :file_name),
        // damit keine SQL-Injection möglich ist.
        $sql = "
            INSERT INTO gallery (user_id, file_name)
            VALUES (:user_id, :file_name)
        ";

        // Das SQL wird vorbereitet, aber noch nicht ausgeführt
        $stmt = $db->prepare($sql);

        // ---------------------------------------------------------
        // 3) Platzhalter mit echten Werten füllen
        // ---------------------------------------------------------
        // Hier werden die PHP-Variablen den SQL-Platzhaltern zugewiesen.
        $stmt->execute([
            ':user_id'   => $userId,
            ':file_name' => $fileName
        ]);

        // ---------------------------------------------------------
        // 4) Prüfen, ob genau ein Datensatz eingefügt wurde
        // ---------------------------------------------------------
        // rowCount() gibt an, wie viele Zeilen betroffen waren.
        // Wenn es genau 1 ist → erfolgreich.
        return $stmt->rowCount() === 1;
    }

    /**
     * Lädt alle Bilder eines bestimmten Benutzers aus der Datenbank.
     *
     * Diese Methode wird vom Controller aufgerufen,
     * wenn die Galerie-Seite angezeigt werden soll.
     *
     * @param int $userId ID des Users
     *
     * @return array Liste mit Bild-Datensätzen (Dateinamen)
     */
    public static function getImagesByUserId($userId)
    {
        // ---------------------------------------------------------
        // 1) Wieder die Datenbankverbindung holen
        // ---------------------------------------------------------
        $db = DatabaseFactory::getFactory()->getConnection();

        // ---------------------------------------------------------
        // 2) SQL-Abfrage erstellen
        // ---------------------------------------------------------
        // Wir wählen nur die Bilder aus,
        // die zu genau diesem Benutzer gehören.
        $sql = "
            SELECT file_name
            FROM gallery
            WHERE user_id = :user_id
        ";

        // SQL vorbereiten
        $stmt = $db->prepare($sql);

        // ---------------------------------------------------------
        // 3) Benutzer-ID einsetzen und Abfrage starten
        // ---------------------------------------------------------
        $stmt->execute([
            ':user_id' => $userId
        ]);

        // ---------------------------------------------------------
        // 4) Ergebnis zurückgeben
        // ---------------------------------------------------------
        // fetchAll() holt alle gefundenen Datensätze auf einmal.
        // Jeder Eintrag enthält z.B.:
        //   ['file_name' => 'bild1.jpg']
        return $stmt->fetchAll();
    }
}
