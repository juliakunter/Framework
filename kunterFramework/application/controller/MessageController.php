<?php

class MessageController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // Zugriff nur für eingeloggte Nutzer
        Auth::checkAuthentication();
    }

    // Zeigt die Nachrichtenübersicht
    public function index()
{
    $userId = Session::get('user_id');

    $this->View->render('message/index', [
        'conversations' => MessageModel::getConversations($userId)
    ]);
}


    // Zeigt ein einzelnes Gespräch
    public function show($partnerId)
    {
        $myId = Session::get('user_id'); // eigene User-ID

        $this->View->render('message/show', [
            // Nachrichten zwischen mir und dem Gesprächspartner
            'messages'  => MessageModel::getConversation($myId, $partnerId),
            'partnerId' => $partnerId
        ]);

        // Nachrichten des Partners als gelesen markieren
        MessageModel::markAsRead($partnerId, $myId);
    }

    // Sendet eine neue Nachricht
    public function send()
    {
        // Prüfen, ob benötigte POST-Daten vorhanden sind
        if (empty($_POST['receiver_id']) || empty($_POST['message_text'])) {
            Session::add('feedback_negative', 'Ungültige Anfrage');
            Redirect::to('message');
        }

        // Nachricht speichern
        MessageModel::sendMessage(
            Session::get('user_id'),     // Absender
            $_POST['receiver_id'],       // Empfänger
            $_POST['message_text']       // Nachricht
        );

        // Zur Unterhaltung weiterleiten
        Redirect::to('message/show/' . $_POST['receiver_id']);
    }

    public function sendTest($receiverId, $text = 'Testnachricht')
{
    MessageModel::sendMessage(
        Session::get('user_id'),
        $receiverId,
        urldecode($text)
    );

    Redirect::to('message/show/' . $receiverId);
}

}