<?php

class MessageModel
{
    /* =========================
       1-zu-1 Nachricht senden
       ========================= */
    public static function sendMessage($receiver_id, $message_text)
    {
        if (!$receiver_id || !$message_text || strlen($message_text) == 0) {
            Session::add('feedback_negative', 'Message sending failed: Invalid data');
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL spSendMessage(:sender_id, :receiver_id, :message_text)";
        $query = $database->prepare($sql);
        $query->execute([
            ':sender_id' => Session::get('user_id'),
            ':receiver_id' => $receiver_id,
            ':message_text' => $message_text
        ]);

        Session::add('feedback_positive', 'Message sent successfully');
        return true;
    }

    /* =========================
       Admin-Gruppennachricht
       ========================= */
    public static function sendGroupMessage($receiver_group, $message_text)
    {
        if (!$receiver_group || !$message_text || strlen($message_text) == 0) {
            Session::add('feedback_negative', 'Group message sending failed');
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL spSendGroupMessage(:sender_id, :receiver_group, :message_text)";
        $query = $database->prepare($sql);
        $query->execute([
            ':sender_id' => Session::get('user_id'),
            ':receiver_group' => $receiver_group,
            ':message_text' => $message_text
        ]);

        Session::add('feedback_positive', 'Group message sent successfully');
        return true;
    }

    /* =========================
       Custom-Gruppennachricht
       ========================= */
    public static function sendCustomGroupMessage($group_id, $message_text)
    {
        if (!$group_id || !$message_text || strlen($message_text) == 0) {
            Session::add('feedback_negative', 'Message sending failed');
            return false;
        }

        if (!GroupModel::isMember($group_id, Session::get('user_id'))) {
            Session::add('feedback_negative', 'You are not a member of this group');
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL spSendCustomGroupMessage(:sender_id, :group_id, :message_text)";
        $query = $database->prepare($sql);
        $query->execute([
            ':sender_id' => Session::get('user_id'),
            ':group_id' => $group_id,
            ':message_text' => $message_text
        ]);

        Session::add('feedback_positive', 'Message sent successfully');
        return true;
    }

    /* =========================
       Gruppen-Konversation
       ========================= */
    public static function getGroupConversation($group_id)
    {
        if (!GroupModel::isMember($group_id, Session::get('user_id'))) {
            return [];
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL spGetGroupConversation(:group_id)";
        $query = $database->prepare($sql);
        $query->execute([':group_id' => $group_id]);

        return $query->fetchAll();
    }

    /* =========================
       1-zu-1 Konversation
       ========================= */
    public static function getConversation($user_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL spGetConversation(:user_id, :current_user_id)";
        $query = $database->prepare($sql);
        $query->execute([
            ':user_id' => $user_id,
            ':current_user_id' => Session::get('user_id')
        ]);

        return $query->fetchAll();
    }

    /* =========================
       Alle Konversationen
       ========================= */
    public static function getAllConversations()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL spGetAllConversations(:current_user_id)";
        $query = $database->prepare($sql);
        $query->execute([
            ':current_user_id' => Session::get('user_id')
        ]);

        return $query->fetchAll();
    }

    /* =========================
       Ungelesene zählen
       ========================= */
    public static function getUnreadCount()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL spGetUnreadCount(:user_id)";
        $query = $database->prepare($sql);
        $query->execute([
            ':user_id' => Session::get('user_id')
        ]);

        $result = $query->fetch();
        return $result ? (int)$result->unread_count : 0;
    }

    /* =========================
       Einzelne Nachricht lesen
       ========================= */
    public static function markAsRead($message_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL spMarkAsRead(:message_id, :user_id)";
        $query = $database->prepare($sql);
        $query->execute([
            ':message_id' => $message_id,
            ':user_id' => Session::get('user_id')
        ]);

        return true;
    }

    /* =========================
       Ganze Konversation lesen
       ========================= */
    public static function markConversationAsRead($user_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL spMarkConversationAsRead(:sender_id, :receiver_id)";
        $query = $database->prepare($sql);
        $query->execute([
            ':sender_id' => $user_id,
            ':receiver_id' => Session::get('user_id')
        ]);

        return true;
    }

    /* =========================
       Admin-Gruppennachrichten
       ========================= */
    public static function getGroupMessages()
    {
        if (Session::get('user_account_type') != 7) {
            return [];
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "CALL spGetAdminGroupMessages()";
        $query = $database->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }
}
