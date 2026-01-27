<div class="container">

    <!-- Überschrift der Galerie-Seite -->
    <h1>Meine Bildergalerie</h1>

    <!-- Box für den Upload-Bereich -->
    <div class="news-box info-blue" 
         style="padding: 20px; border: 1px solid #ddd; margin-bottom: 20px;">

        <!-- Upload-Formular -->
        <!-- action: URL zum GalleryController -> upload()-Methode -->
        <!-- method="post": Datei wird per POST gesendet -->
        <!-- enctype="multipart/form-data": zwingend notwendig für Datei-Uploads -->
        <form action="<?php echo Config::get('URL'); ?>gallery/upload" 
              method="post" 
              enctype="multipart/form-data">

            <!-- Beschriftung für das File-Input-Feld -->
            <label>Neues Bild wählen:</label>

            <!-- Datei-Auswahlfeld -->
            <!-- name="image_file" muss mit $_FILES['image_file'] im Controller übereinstimmen -->
            <input type="file" name="image_file" required>

            <!-- Absende-Button -->
            <button type="submit" 
                    style="background-color: #28a745; color: white; padding: 5px 15px; border: none; cursor: pointer;">
                Hochladen
            </button>
        </form>
    </div>

    <!-- Container für die Anzeige der Bilder -->
    <!-- Flexbox sorgt dafür, dass die Bilder nebeneinander angezeigt werden -->
    <div style="display: flex; flex-wrap: wrap; gap: 15px;">

        <?php 
        // Prüfen, ob überhaupt Bilder vorhanden sind
        if ($this->images) { 

            // Jedes Bild einzeln durchgehen
            foreach ($this->images as $image) { ?>
                
                <!-- Einzelnes Bild in einer kleinen Box -->
                <div style="border: 1px solid #ccc; padding: 5px; background: #f9f9f9;">

                    <!-- Bild anzeigen -->
                    <!-- URL wird dynamisch zusammengesetzt -->
                    <!-- Basis-URL aus der Config -->
                    <!-- user_ID aus der Session -->
                    <!-- Dateiname aus der Datenbank -->
                    <img 
                        src="<?php echo Config::get('URL'); ?>
                             gallery/gallery-images/user_<?php echo Session::get('user_id'); ?>/<?php 
                             echo $image->file_name; ?>" 
                        alt="User Image" 
                        style="width: 200px; height: 150px; object-fit: cover; display: block;">
                </div>

            <?php } 

        } else { ?>

            <!-- Wird angezeigt, wenn der User noch keine Bilder hochgeladen hat -->
            <p>Noch keine Bilder hochgeladen.</p>

        <?php } ?>
    </div>
</div>

<style>
    /* Wichtig:
       Stellt sicher, dass das Navigationsmenü über den Bildern liegt
       und nicht verdeckt wird */
    .navigation { 
        z-index: 1000 !important; 
        position: relative; 
    }
</style>
