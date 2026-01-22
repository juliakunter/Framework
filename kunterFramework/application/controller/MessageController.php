<?php

class MessageController extends Controller
{
    public function __construct()
    {
        parent::__construct();
         Auth::checkAuthentication();
    }

    public function index()
    {
        $userId = Session::get('user_id');
      // $this->view->conversations =
        $this->View->render('message/index', array(
            'conversations' => MessageModel::getAllConversations()
        ));
    }

    public function show($partnerId)
    {
        $myId = Session::get('user_id');
        $this->view->messages = MessageModel::getConversation($myId, $partnerId);
        MessageModel::markAsRead($partnerId, $myId);
        
    }

    public static function getConversations($userId)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "
            SELECT 
                IF(m.sender_id = :uid, m.empfaenger_id, m.sender_id) AS partner_id,
                u.user_name AS partner_name,
                m.message_text,
                m.created_at,
                m.gelesen
            FROM messages m
            JOIN users u 
              ON u.user_id = IF(m.sender_id = :uid, m.empfaenger_id, m.sender_id)
            WHERE m.message_id IN (
                SELECT MAX(message_id)
                FROM messages
                WHERE sender_id = :uid OR empfaenger_id = :uid
                GROUP BY LEAST(sender_id, empfaenger_id), GREATEST(sender_id, empfaenger_id)
            )
            ORDER BY m.created_at DESC
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([':uid' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getConversation($myId, $partnerId)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $stmt = $db->prepare("
            SELECT *
            FROM messages
            WHERE 
                (sender_id = :me AND empfaenger_id = :partner)
                OR
                (sender_id = :partner AND empfaenger_id = :me)
            ORDER BY created_at ASC
        ");

        $stmt->execute([
            ':me' => $myId,
            ':partner' => $partnerId
        ]);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function sendMessage($senderId, $receiverId, $text)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO messages 
            (sender_id, empfaenger_id, message_text, gelesen, created_at)
            VALUES (:s, :r, :t, 0, NOW())
        ");

        $stmt->execute([
            ':s' => $senderId,
            ':r' => $receiverId,
            ':t' => trim(strip_tags($text))
        ]);
    }

    public static function markAsRead($senderId, $receiverId)
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $stmt = $db->prepare("
            UPDATE messages
            SET gelesen = 1
            WHERE sender_id = :s
              AND empfaenger_id = :r
        ");

        $stmt->execute([
            ':s' => $senderId,
            ':r' => $receiverId
        ]);
    }
}
