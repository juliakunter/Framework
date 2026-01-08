<?php

// Definition der Model-Klasse für Nachrichten
class MessageModel
{
    // Private statische Methode zum Holen der Datenbankverbindung
    private static function db()
    {
        // Gibt die aktive Datenbankverbindung über die DatabaseFactory zurück
        return DatabaseFactory::getFactory()->getConnection();
    }

    // Methode zum Speichern einer neuen Nachricht
    public static function sendMessage($senderId, $receiverId, $text)
    {
        // Prüft, ob Absender, Empfänger oder Text leer sind
        if (empty($senderId) || empty($receiverId) || empty($text)) {
            return false; // Abbruch bei fehlenden Daten
        }

        // SQL-Query zum Einfügen einer neuen Nachricht in die Tabelle messages
        $sql = "
            INSERT INTO messages (sender_id, empfaenger_id, message_text)
            VALUES (:sender, :receiver, :text)
        ";

        // Bereitet das SQL-Statement vor und führt es mit den übergebenen Werten aus
        return self::db()->prepare($sql)->execute([
            ':sender'   => $senderId,   // ID des Absenders
            ':receiver' => $receiverId, // ID des Empfängers
            ':text'     => $text        // Nachrichtentext
        ]);
    }

    // Methode zum Laden eines kompletten Gesprächs zwischen zwei Usern
    public static function getConversation($userId, $partnerId)
    {
        // SQL-Query zum Auslesen aller Nachrichten zwischen zwei Usern
        $sql = "
            SELECT *
            FROM messages
            WHERE (sender_id = :me AND empfaenger_id = :partner)
               OR (sender_id = :partner AND empfaenger_id = :me)
            ORDER BY timestamp ASC
        ";

        // Bereitet das SQL-Statement vor
        $query = self::db()->prepare($sql);

        // Führt das Statement mit den entsprechenden User-IDs aus
        $query->execute([':me' => $userId, ':partner' => $partnerId]);

        // Gibt alle gefundenen Nachrichten zurück
        return $query->fetchAll();
    }

    // Methode zum Markieren von Nachrichten als gelesen
    public static function markAsRead($senderId, $receiverId)
    {
        // SQL-Query zum Setzen des gelesen-Status auf 1
        $sql = "
            UPDATE messages
            SET gelesen = 1
            WHERE sender_id = :sender AND empfaenger_id = :receiver
        ";

        // Bereitet das Statement vor und führt es aus
        return self::db()->prepare($sql)->execute([
            ':sender'   => $senderId,   // Absender der Nachrichten
            ':receiver' => $receiverId  // Empfänger der Nachrichten
        ]);
    }

    // Methode zum Laden aller Unterhaltungen eines Users inklusive ungelesener Nachrichten
    public static function getConversations($userId)
    {
        // SQL-Query zum Gruppieren der Chats nach Chat-Partner
        $sql = "
            SELECT 
                IF(sender_id = :me, empfaenger_id, sender_id) AS partner_id,
                MAX(timestamp) AS last_time,
                SUM(CASE WHEN empfaenger_id = :me AND gelesen = 0 THEN 1 ELSE 0 END) AS unread
            FROM messages
            WHERE sender_id = :me OR empfaenger_id = :me
            GROUP BY partner_id
            ORDER BY last_time DESC
        ";

        // Bereitet das SQL-Statement vor
        $query = self::db()->prepare($sql);

        // Führt das Statement mit der User-ID aus
        $query->execute([':me' => $userId]);

        // Gibt alle Unterhaltungen zurück
        return $query->fetchAll();
    }

    // Methode zum Zählen ungelesener Nachrichten von einem bestimmten Partner
    public static function countUnreadMessagesFrom($senderId, $receiverId)
    {
        // SQL-Query zum Zählen ungelesener Nachrichten
        $sql = "
            SELECT COUNT(*) 
            FROM messages 
            WHERE sender_id = :sender AND empfaenger_id = :receiver AND gelesen = 0
        ";

        // Bereitet das SQL-Statement vor
        $query = self::db()->prepare($sql);

        // Führt das Statement mit Absender- und Empfänger-ID aus
        $query->execute([':sender' => $senderId, ':receiver' => $receiverId]);

        // Gibt die Anzahl als Integer zurück
        return (int)$query->fetchColumn();
    }

    // Methode zum Zählen aller ungelesenen Nachrichten eines Users
    public static function countUnreadMessages($userId)
    {
        // SQL-Query zum Zählen aller ungelesenen Nachrichten für einen User
        $sql = "
            SELECT COUNT(*) 
            FROM messages 
            WHERE empfaenger_id = :user AND gelesen = 0
        ";

        // Bereitet das SQL-Statement vor
        $query = self::db()->prepare($sql);

        // Führt das Statement mit der User-ID aus
        $query->execute([':user' => $userId]);

        // Gibt die Anzahl als Integer zurück
        return (int)$query->fetchColumn();
    }

    // Methode zum Ermitteln aller Benutzer, mit denen Nachrichten existieren
    public static function getUsersForMessaging($userId)
    {
        // SQL-Query zum Auslesen aller eindeutigen Chat-Partner
        $sql = "
            SELECT DISTINCT
                IF(sender_id = :me, empfaenger_id, sender_id) AS user_id
            FROM messages
            WHERE sender_id = :me OR empfaenger_id = :me
        ";

        // Bereitet das SQL-Statement vor
        $query = self::db()->prepare($sql);

        // Führt das Statement mit der User-ID aus
        $query->execute([':me' => $userId]);

        // Gibt nur die user_id-Spalte als Array zurück
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }
}
