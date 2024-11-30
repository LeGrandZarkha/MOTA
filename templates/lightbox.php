<div class="lightbox-overlay">
    <div class="lightbox-arrow-left">
        <i class="fa-solid fa-arrow-left"></i>
        <p>Précédente</p>
    </div>
    <div class="lightbox">
        <div class="lightbox-top"></div>
        <div class="lightbox-middle">
            <img id="lightbox-image" src="" alt="">
        </div>
        <div class="lightbox-bottom">
            <p id="lightbox-ref"></p>
            <p id="lightbox-category">
                <?php
                // Récupérer les termes de la taxonomie 'categorie'
                $terms = get_the_terms(get_the_ID(), 'categorie');
                if ($terms && !is_wp_error($terms)) {
                    $categorie_names = array();
                    foreach ($terms as $term) {
                        $categorie_names[] = esc_html($term->name);
                    }
                    echo implode(', ', $categorie_names);
                } else {
                    echo 'N/A'; // Afficher un message si aucun terme n'est trouvé
                }
                ?>
            </p>
        </div>
    </div>
    <div class="lightbox-arrow-right">
        <i class="lightbox-close-button fa-solid fa-xmark"></i>
        <p>Suivante</p>
        <i class="fa-solid fa-arrow-right"></i>
    </div>
</div>