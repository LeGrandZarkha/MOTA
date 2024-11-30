jQuery(document).ready(function($) {
    let offset = 0; // Variable pour gérer la pagination.
    let lightboxPhotos = []; // On initialise a vide pour eviter les erreurs

    // Fonction pour charger les photos via AJAX
    function loadPhotos(additionalOffset = 0) {
        var selectedCategory = $('.dropdown-category .dropdown-option.selected').data('value') || '';
        var selectedFormat = $('.dropdown-format .dropdown-option.selected').data('value') || '';
        var selectedDate = $('.dropdown-date .dropdown-option.selected').data('value') || '';
        $.ajax({
            url: ajax_object.ajaxurl, // URL pour l'admin-ajax.php
            type: 'POST', // Méthode HTTP
            data: {
                action: 'load_photos', // Action déclarée dans WordPress
                categorie: selectedCategory, // Valeur sélectionnée pour la catégorie
                format: selectedFormat, // Valeur sélectionnée pour le format
                date_order: selectedDate, // Valeur sélectionnée pour l'ordre des dates
                offset: offset + additionalOffset, // Offset calculé pour la pagination
            },
            success: function(response) {
                let result = JSON.parse(response); // Analyse la réponse JSON renvoyée

                // Gestion du bouton "Voir plus"
                if (result.count < 9) { 
                    $('#load-more').hide(); // Cacher le bouton s'il n'y a pas assez de photos
                } else {
                    $('#load-more').show(); // Afficher le bouton pour charger plus
                }

                // Ajouter les nouvelles photos au conteneur
                let photos = $(result.html).slice(0, 8); // Sélectionner uniquement les 8 premières photos
                $('.catalogue-photos').append(photos); // Ajouter les photos au DOM
                offset += 8; // Incrémenter l'offset pour les futures requêtes

                lightboxPhotos = lightboxPhotos.concat((result.lightbox_photos).slice(0,8));
                // Récupérer les données pour la lightbox
                //.slice pour n'afficher que 8 photos dans la lightbox
                //.concat() pour ajouter les données au tableau et ne pas écraser les anciennes

                addHoverEffects(); // Appliquer les effets de survol
                initializeLightbox(lightboxPhotos); // Initialiser la lightbox avec les nouvelles données
            }
        });
    }

    // Charger les photos au chargement initial de la page
    loadPhotos();

    // Gérer les clics sur les options de filtre
$('.dropdown-category, .dropdown-format, .dropdown-date').on('click', '.dropdown-option', function() {
    var dropdown = $(this).closest('.dropdown');  // Trouver le dropdown parent
    var selectedOption = $(this); // L'option sélectionnée
    var selectedText = selectedOption.text();  // Le texte de l'option sélectionnée

    // Mettre à jour le texte du "sélecteur"
    dropdown.find('.dropdown-selected').text(selectedText);

    // Mettre à jour la classe 'selected' (pour l'option sélectionnée)
    dropdown.find('.dropdown-option').removeClass('selected');  // Supprimer la classe 'selected' de toutes les options
    selectedOption.addClass('selected');  // Ajouter la classe 'selected' à l'option actuelle

    // Réinitialiser le conteneur des photos et le tableau JSON
    $('.catalogue-photos').empty();
    lightboxPhotos = [];  // Réinitialiser le tableau JSON
    offset = 0;  // Réinitialiser l'offset

    // Récupérer les valeurs des filtres sélectionnés
    var selectedCategory = $('.dropdown-category .dropdown-option.selected').data('value') || '';
    var selectedFormat = $('.dropdown-format .dropdown-option.selected').data('value') || '';
    var selectedDate = $('.dropdown-date .dropdown-option.selected').data('value') || '';

    // Recharger les photos avec les nouveaux filtres
    loadPhotos({
        categorie: selectedCategory,
        format: selectedFormat,
        date_order: selectedDate,
        offset: offset
    });
});

    // Gérer le clic sur le bouton "Voir plus"
    $('#load-more').on('click', function() {
        loadPhotos(); // Charger les photos supplémentaires
    });

    // Ajouter des effets au survol des photos
    function addHoverEffects() {
        $('.photo-item').each(function() {
            const $this = $(this);
            const $icons = $this.find('i'); // Tous les éléments <i> dans chaque photo
            const $eyeIcon = $this.find('.fa-eye'); // Icône "oeil"
            const $photoInfos = $this.find('.photo-item-infos'); // Conteneur d'informations sur la photo

            // Effet de survol sur toute la photo
            $this.on('mouseover', function() {
                $this.addClass('hover-effect'); // Ajouter une classe CSS
                $icons.css('opacity', '1'); // Rendre les icônes visibles
            });

            $this.on('mouseleave', function() {
                $this.removeClass('hover-effect'); // Retirer la classe CSS
                $icons.css('opacity', '0'); // Masquer les icônes
            });

            // Effet spécifique pour l'icône "oeil"
            $eyeIcon.on('mouseover', function() {
                $photoInfos.addClass('show-infos'); // Afficher les informations
            });

            $eyeIcon.on('mouseleave', function() {
                $photoInfos.removeClass('show-infos'); // Masquer les informations
            });

            // Redirection vers l'article au clic sur l'icône "oeil"
            $eyeIcon.on('click', function() {
                const postURL = $this.data('id'); // ID de l'article
                window.location.href = postURL; // Redirection
            });
        });
    }

    // Initialiser la lightbox
    function initializeLightbox(lightboxPhotos) {
        let currentIndex = 0; // Index actuel de la photo dans la lightbox

        // Fonction pour ouvrir la lightbox
        function openLightbox(index) {
            currentIndex = index; // Mettre à jour l'index courant
            loadPhoto(currentIndex); // Charger la photo correspondante
            $('.lightbox-overlay').css('display', 'flex'); // Afficher la lightbox
        }

        // Fonction pour charger une photo dans la lightbox
        function loadPhoto(index) {
            const photo = lightboxPhotos[index]; // Photo actuelle
            if (photo) {
                $('#lightbox-image').attr('src', photo.url); // Définir l'image
                $('#lightbox-ref').text(photo.ref); // Référence de la photo
                $('#lightbox-category').text(photo.category); // Catégorie
            }
        }

        // Fonction pour fermer la lightbox
        function closeLightbox() {
            $('.lightbox-overlay').css('display', 'none'); // Masquer la lightbox
        }

        // Navigation dans la lightbox avec les flèches
        $('.lightbox-arrow-left').on('click', function() {
            currentIndex = (currentIndex - 1 + lightboxPhotos.length) % lightboxPhotos.length; // Photo précédente
            loadPhoto(currentIndex); // Charger la nouvelle photo
        });

        $('.lightbox-arrow-right').on('click', function() {
            currentIndex = (currentIndex + 1) % lightboxPhotos.length; // Photo suivante
            loadPhoto(currentIndex); // Charger la nouvelle photo
        });

        // Bouton pour fermer la lightbox
        $('.lightbox-close-button').on('click', closeLightbox);

        // Ajouter les événements pour ouvrir la lightbox sur chaque photo
        $('.photo-item').each(function() {
            const $this = $(this);
            const $fullScreenButton = $this.find('.fa-expand'); // Icône plein écran

            $fullScreenButton.on('click', function() {
                const index = parseInt($this.data('index')); // Index de la photo
                openLightbox(index); // Ouvrir la lightbox
            });
        });
    }
});


// Display & Close Contact Pop Up
jQuery(document).ready(function($) {

    const $menuContactButton = $('#menu-item-86 > a');
    const $popup = $('.contact-popup-overlay');
    const $closeButton = $('.popup-close');
    const $ref_photo = $('#ref_photo');

    $menuContactButton.on('click', function(){
        $ref_photo.val('');
        $popup.show();
    })

    $closeButton.on('click', function(){
        $popup.hide();
    })
})

// select

jQuery(document).ready(function() {

    var $j = jQuery.noConflict();  // Maintenant, jQuery sera accessible via $j, pas $

    const dropdowns = $j('.dropdown');  // Sélectionne tous les dropdowns

    // Lorsque l'on clique sur un menu déroulant
    dropdowns.each(function() {
        const dropdown = $j(this);
        const select = dropdown.find('.dropdown-select');
        const menu = dropdown.find('.dropdown-menu');
        const options = dropdown.find('.dropdown-option');

        // Ouvre le menu au clic sur l'élément .dropdown-select
        select.on('click', function() {
            // Ferme tous les autres menus avant d'ouvrir celui sélectionné
            dropdowns.find('.dropdown-menu').not(menu).removeClass('show');

            // Bascule l'affichage du menu pour celui cliqué
            menu.toggleClass('show');
        });

        // Lorsque l'on clique sur une option du menu
        options.each(function() {
            const option = $j(this);
            option.on('click', function() {
                options.removeClass('selected');
                select.text(option.text()); // Met à jour le texte de l'élément sélectionné
                option.addClass('selected');
                menu.removeClass('show'); // Ferme le menu après sélection
            });
        });
    });

    // Ferme le menu si l'utilisateur clique en dehors
    $j(document).on('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            $j('.dropdown-menu').removeClass('show');
        }
    });
});

