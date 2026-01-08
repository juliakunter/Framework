<?php

class MessageModel
{
    public static function getInbox()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            SELECT 
                m.message_id,
                m.message_text,
                m.created_at,
                m.gelesen,
                u.user_name AS sender_name
            FROM messages m
            JOIN users u ON u.user_id = m.sender_id
            WHERE m.empfaenger_id = :uid
            ORDER BY m.created_at DESC
        ";

        $query = $db->prepare($sql);
        $query->execute([
            ':uid' => Session::get('user_id')
        ]);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getMessageById($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            SELECT message_text, created_at 
            FROM messages 
            WHERE message_id = :id
        ";

        $query = $db->prepare($sql);
        $query->execute([':id' => $id]);

        return $query->fetch(PDO::FETCH_OBJ);
    }

    public static function markAsRead($id)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            UPDATE messages 
            SET gelesen = 1 
            WHERE message_id = :id
        ";

        $query = $db->prepare($sql);
        $query->execute([':id' => $id]);
    }

    public static function sendMessage($senderId, $empfaengerId, $text)
    {
        if (trim($text) === '') {
            return false;
        }

        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            INSERT INTO messages (sender_id, empfaenger_id, message_text, gelesen)
            VALUES (:s, :e, :t, 0)
        ";

        $query = $db->prepare($sql);
        return $query->execute([
            ':s' => $senderId,
            ':e' => $empfaengerId,
            ':t' => $text
        ]);
    }

    public static function countUnread()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            SELECT COUNT(*) 
            FROM messages 
            WHERE empfaenger_id = :uid 
            AND gelesen = 0
        ";

        $query = $db->prepare($sql);
        $query->execute([
            ':uid' => Session::get('user_id')
        ]);

        return (int)$query->fetchColumn();
    }
}
