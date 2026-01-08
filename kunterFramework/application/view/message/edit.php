<div class="container">
    <h2>Notiz bearbeiten</h2>

    <?php $this->renderFeedbackMessages(); ?>

    <?php if (!empty($this->note)) : ?>
        <form method="post" action="<?= Config::get('URL'); ?>note/update">
            <input type="hidden" name="note_id"
                   value="<?= htmlspecialchars($this->note->note_id); ?>">

            <label>Notiztext</label>
            <input type="text"
                   name="note_text"
                   value="<?= htmlspecialchars($this->note->note_text); ?>"
                   required>

            <button type="submit">Speichern</button>
            <a href="<?= Config::get('URL'); ?>note/index">Abbrechen</a>
        </form>
    <?php else : ?>
        <p>Diese Notiz existiert nicht.</p>
    <?php endif; ?>
</div>
 