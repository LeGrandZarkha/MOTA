// Affichage de la Popup de contact
jQuery(document).ready(function($) {
    const $contactButton = $('.contact-photo-button');
    const $menuContactButton = $('#menu-item-86 > a');
    const $popup = $('.contact-popup-overlay');
    const $closeButton = $('.popup-close');
    const $refPhoto = $('#ref_photo');

    // Affichage de la Popup
    $contactButton.on('click', function() {
        // Affiche le popup
        $popup.show();

        // Vérifie si motaData et motaData.reference existent
        if (typeof motaData !== 'undefined' && motaData.reference) {
            const reference = motaData.reference;
            $refPhoto.val(reference);
        }
    });

    // Quand on clique sur le menu de contact
    $menuContactButton.on('click', function() {
        $refPhoto.val('');
        $popup.show();

        const $burgerMenu = $('.burger-menu');
        const $navMenu = $('#menu-primary');
        const $navArea = $('.menu-area');
        const $header = $('header');
        const $navMenuLis = $navMenu.find('li');
        $burgerMenu.add($navArea).add($navMenu).add($header).add($navMenuLis).removeClass("expanded");
    });

    // Fermeture de la Popup
    $closeButton.on('click', function() {
        $popup.hide();
    });
});



// Affichage des thumbnails de photo en relation
jQuery(document).ready(function($) {
    const $thumbnailImage = $('#thumbnail-image');
    const $arrowLeft = $('.arrow-left');
    const $arrowRight = $('.arrow-right');

    if ($arrowLeft.length && $arrowRight.length && $thumbnailImage.length) {
        // URL de l'image d'origine
        const originalThumbnailUrl = $thumbnailImage.data('thumbnail');
        const prevThumbnailUrl = $arrowLeft.data('thumbnail');
        const nextThumbnailUrl = $arrowRight.data('thumbnail');

        // Gestion de la flèche droite
        if (nextThumbnailUrl) { 
            $arrowRight.css('opacity', '1');
            $arrowRight.on('mouseenter', function() {
                $thumbnailImage.attr('src', nextThumbnailUrl).css('opacity', '1'); // Affiche l'image miniature suivante
            }).on('mouseleave', function() {
                $thumbnailImage.attr('src', originalThumbnailUrl).css('opacity', '1'); // Remet l'image d'origine
            }).on('click', function(){
                const postURL = $(this).data('id-next'); // Récupère l'ID de l'article
                // Redirection vers l'article photo
                window.location.href = postURL;
            });
        } else {
            $arrowRight.css('opacity', '0'); // Cacher la flèche si aucune miniature
        }

        // Gestion de la flèche gauche
        if (prevThumbnailUrl) {
            $arrowLeft.css('opacity', '1');
            $arrowLeft.on('mouseenter', function() {
                $thumbnailImage.attr('src', prevThumbnailUrl).css('opacity', '1'); // Affiche l'image miniature précédente
            }).on('mouseleave', function() {
                $thumbnailImage.attr('src', originalThumbnailUrl).css('opacity', '1'); // Remet l'image d'origine
            }).on('click', function(){
                const postURL = $(this).data('id-prev'); // Récupère l'ID de l'article
                // Redirection vers l'article photo
                window.location.href = postURL;
            });
        } else {
            $arrowLeft.css('opacity', '0'); // Cacher la flèche si aucune miniature
        }
    }
});


/******  Show related photos infos   ******/

jQuery(document).ready(function($) {
    const $relatedPhotoDivs = $('.single-related-photo');

    $relatedPhotoDivs.each(function() {
        const $div = $(this);
        const $icons = $div.find('i'); // Sélectionne tous les éléments <i> dans chaque div
        const $eyeIcon = $div.find('.fa-eye'); // Sélectionne l'icône avec la classe fa-eye
        const $photoInfos = $div.find('.related-photo-infos'); // Sélectionne le conteneur des infos de la photo

        // Affiche l'effet d'ombre et les icônes au survol de la div
        $div.on('mouseover', function() {
            $div.addClass('hover-effect');
            $icons.css('opacity', '1'); // Affiche chaque icône
        });

        $div.on('mouseleave', function() {
            $div.removeClass('hover-effect');
            $icons.css('opacity', '0'); // Cache chaque icône
        });

        // Affiche le paragraphe lors du survol de l'icône fa-eye
        $eyeIcon.on('mouseover', function() {
            $photoInfos.addClass('show-info'); // Affiche les infos de la photo
        });

        $eyeIcon.on('mouseleave', function() {
            $photoInfos.removeClass('show-info'); // Cache les infos de la photo
        });

        $eyeIcon.on('click', function() {
            const postURL = $div.data('url'); // Chemin vers l'article correspondant
            window.location = postURL; // On redirige sur l'article correspondant

            // Redirection vers l'article photo
            window.location.href = postURL;
        });
    });
});


// Lightbox trigger

jQuery(document).ready(function($) {
    const $lightbox = $('.lightbox-overlay');
    const $lightboxCloseButton = $('.lightbox-close-button');
    const $singleRelatedPhotos = $('.single-related-photo');
    const $lightboxRef = $('#lightbox-ref');
    const $lightboxImage = $('#lightbox-image');
    const $lightboxCategory = $('#lightbox-category');

    let currentIndex = 0;

    // Charger une image dans la lightbox
    function loadPhoto(index) {
        if (photoData[index]) {
            $lightboxImage.attr('src', photoData[index].url);
            $lightboxRef.text(photoData[index].ref);
            $lightboxCategory.text(photoData[index].category);
            currentIndex = index;
        }
    }

    // Initialiser les événements sur chaque photo
    $singleRelatedPhotos.each(function(index) {
        const $div = $(this);
        const $fullScreenButton = $div.find('.fa-expand');
        const $lightboxThumbnail = $div.find('img');
        const photoRef = $div.data('ref');

        // Gestion du clic pour ouvrir la lightbox
        $fullScreenButton.on('click', function() {
            if ($lightboxThumbnail.length) {
                $lightboxImage.attr('src', $lightboxThumbnail.data('url'));
                $lightboxRef.text(photoRef);
                $lightbox.css('display', 'flex');
            } else {
                console.error('Aucune image trouvée dans ce div');
            }
        });
    });

    // Navigation avec les flèches directionnelles
    $('.lightbox-arrow-right').on('click', function() {
        currentIndex = (currentIndex + 1) % photoData.length; // Boucle vers la première image après la dernière
        loadPhoto(currentIndex);
    });

    $('.lightbox-arrow-left').on('click', function() {
        currentIndex = (currentIndex - 1 + photoData.length) % photoData.length; // Boucle vers la dernière image après la première
        loadPhoto(currentIndex);
    });

    // Gestion de la fermeture de la lightbox
    $lightboxCloseButton.on('click', function() {
        $lightbox.css('display', 'none');
    });
});

//Gestion du menu Burger
jQuery(document).ready(function($){
    const $burgerMenu = $('.burger-menu');
    const $navMenu = $('#menu-primary');
    const $navArea = $('.menu-area');
    const $header = $('header');

    $burgerMenu.on('click', function(){
        const $navMenuLis = $navMenu.find('li');
        if(!$burgerMenu.hasClass('expanded')){
            
            $burgerMenu.add($navArea).add($navMenu).add($header).addClass("expanded");

            // Animation 'Staggered'
            $navMenuLis.each(function(index){
                $(this).delay(index * 300).queue(function(next){
                    console.log('animation triggered :' + index)
                    $(this).addClass('expanded');
                    next(); // Continue avec le prochain élément
                });
            });
        } else {
            $burgerMenu.add($navArea).add($navMenu).add($header).add($navMenuLis).removeClass("expanded");
        }    
    });
});
