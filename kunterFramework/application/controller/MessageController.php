<?php

class MessageController extends Controller
{
    public function index()
    {
        $this->View->messages = MessageModel::getInbox();
        $this->View->render('message/index');
    }

    public function read($messageId)
    {
        MessageModel::markAsRead((int)$messageId);
        $message = MessageModel::getMessageById((int)$messageId);

        echo json_encode($message);
        exit;
    }

    public function send()
    {
        MessageModel::sendMessage(
            Session::get('user_id'),
            $_POST['empfaenger_id'],
            $_POST['message_text']
        );

        Redirect::to('message/index');
    }
}
