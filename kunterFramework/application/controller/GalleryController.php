<?php

// Controller für die Galerie.
// Kümmert sich um Benutzeraktionen und verbindet Model + View.
class GalleryController extends Controller
{
    // Wird automatisch ausgeführt, wenn der Controller erstellt wird
    public function __construct() {
        parent::__construct();

        // Sicherheit: nur eingeloggte User dürfen hier rein
        Auth::checkAuthentication();
    }

    // Standard-Seite der Galerie anzeigen
    public function index() {

        // Aktuelle User-ID aus der Session holen
        $userId = Session::get('user_id');

        // Bilder dieses Users aus der Datenbank laden
        $images = GalleryModel::getImagesByUserId($userId);

        // View laden und die Bilder übergeben
        $this->View->render('gallery/index', [
            'images' => $images
        ]);
    }

    // Wird aufgerufen, wenn ein Bild hochgeladen wird
    public function upload() {

        // Prüfen, ob überhaupt eine Datei gesendet wurde
        if (isset($_FILES['image_file'])) {

            // User-ID aus der Session holen
            $userId = Session::get('user_id');

            // Neuen Dateinamen erzeugen, damit nichts überschrieben wird
            $fileName = time() . "_" . $_FILES['image_file']['name'];

            // Zielordner auf dem Server bestimmen
            $targetDir =
                dirname(__DIR__, 2) .
                '/public/gallery/gallery-images/user_' .
                $userId . '/';

            // Ordner anlegen, falls er noch nicht existiert
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Datei aus dem Temp-Ordner in den Zielordner verschieben
            if (move_uploaded_file(
                $_FILES['image_file']['tmp_name'],
                $targetDir . $fileName
            )) {

                // Nach erfolgreichem Upload → in DB speichern
                GalleryModel::addImage($userId, $fileName);
            }
        }

        // Danach zurück zur Galerie-Seite
        Redirect::to('gallery/index');
    }
}
