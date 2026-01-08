<?php

class MessageModel
{
    private static function db()
    {
        return DatabaseFactory::getFactory()->getConnection();
    }

    // Nachricht speichern
    public static function sendMessage($senderId, $receiverId, $text)
    {
        if (empty($senderId) || empty($receiverId) || empty($text)) {
            return false; // Abbruch bei fehlenden Daten
        }

        $sql = "
            INSERT INTO messages (sender_id, empfaenger_id, message_text)
            VALUES (:sender, :receiver, :text)
        ";

        return self::db()->prepare($sql)->execute([
            ':sender'   => $senderId,
            ':receiver' => $receiverId,
            ':text'     => $text
        ]);
    }

    // Gespräch zwischen zwei Usern laden
    public static function getConversation($userId, $partnerId)
    {
        $sql = "
            SELECT *
            FROM messages
            WHERE (sender_id = :me AND empfaenger_id = :partner)
               OR (sender_id = :partner AND empfaenger_id = :me)
            ORDER BY timestamp ASC
        ";

        $query = self::db()->prepare($sql);
        $query->execute([':me' => $userId, ':partner' => $partnerId]);

        return $query->fetchAll();
    }

    // Nachrichten als gelesen markieren
    public static function markAsRead($senderId, $receiverId)
    {
        $sql = "
            UPDATE messages
            SET gelesen = 1
            WHERE sender_id = :sender AND empfaenger_id = :receiver
        ";

        return self::db()->prepare($sql)->execute([
            ':sender'   => $senderId,
            ':receiver' => $receiverId
        ]);
    }

    // Alle Unterhaltungen des Users mit ungelesenen Nachrichten
    public static function getConversations($userId)
    {
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

        $query = self::db()->prepare($sql);
        $query->execute([':me' => $userId]);

        return $query->fetchAll();
    }

    // Ungelesene Nachrichten von einem Partner zählen
    public static function countUnreadMessagesFrom($senderId, $receiverId)
    {
        $sql = "
            SELECT COUNT(*) 
            FROM messages 
            WHERE sender_id = :sender AND empfaenger_id = :receiver AND gelesen = 0
        ";
        $query = self::db()->prepare($sql);
        $query->execute([':sender' => $senderId, ':receiver' => $receiverId]);
        return (int)$query->fetchColumn();
    }

    // Gesamtanzahl ungelesener Nachrichten
    public static function countUnreadMessages($userId)
    {
        $sql = "
            SELECT COUNT(*) 
            FROM messages 
            WHERE empfaenger_id = :user AND gelesen = 0
        ";
        $query = self::db()->prepare($sql);
        $query->execute([':user' => $userId]);
        return (int)$query->fetchColumn();
    }

    // Alle Benutzer, die mit mir Nachrichten haben (für Profil-Liste)
    public static function getUsersForMessaging($userId)
    {
        $sql = "
            SELECT DISTINCT
                IF(sender_id = :me, empfaenger_id, sender_id) AS user_id
            FROM messages
            WHERE sender_id = :me OR empfaenger_id = :me
        ";
        $query = self::db()->prepare($sql);
        $query->execute([':me' => $userId]);

        return $query->fetchAll(PDO::FETCH_COLUMN);
    }
}