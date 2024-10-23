<?php
$nom = get_field('nom', get_the_ID());
$email = get_field('email', get_the_ID());
$ref_photo = get_field('ref_photo', get_the_ID());
$message = get_field('message', get_the_ID());
?>

<div class="contact-popup-overlay">
    <div class="contact-popup">
        <div class="popup-header">
            <h3>CONTACT</h3>
            <span class="popup-close"><i class="fa-solid fa-xmark"></i></span>
        </div>
        <div class="popup-form">
            <form action="">
                <label for="nom">NOM</label>
                <input type="text" name="nom" id="nom" value="<?php echo esc_attr($nom); ?>">

                <label for="email">E-MAIL</label>
                <input type="text" name="email" id="email" value="<?php echo esc_attr($email); ?>">

                <label for="ref_photo">REF_PHOTO</label>
                <input type="text" name="ref_photo" id="ref_photo" value="<?php echo esc_attr($ref_photo); ?>" readonly>

                <label for="message">MESSAGE</label>
                <input type="textarea" name="message" id="message" wrap='soft' value="<?php echo esc_attr($message); ?>">

                <button type="submit" id="photo-contact-button">Envoyer</button>
            </form>
        </div>
    </div>
</div>