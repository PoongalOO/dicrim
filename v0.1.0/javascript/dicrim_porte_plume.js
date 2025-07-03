/**
 * JavaScript pour assurer l'activation du porte_plume dans les formulaires DICRIM
 */
(function($) {
    $(document).ready(function() {
        // S'assurer que tous les textarea avec la classe textarea_porte_plume ont bien le porte_plume
        if (typeof $.fn.markItUp !== 'undefined') {
            $('.textarea_porte_plume:not(.markItUpEditor)').each(function() {
                var textarea = $(this);
                // Vérifier si le textarea n'a pas déjà été initialisé
                if (!textarea.hasClass('markItUpEditor') && !textarea.parent().hasClass('markItUpContainer')) {
                    // Récupérer la barre d'outils appropriée en fonction de l'ID
                    var barre = 'edition'; // Barre par défaut
                    
                    // Appliquer la barre d'outils
                    if (typeof markitup_settings !== 'undefined' && typeof markitup_settings[barre] !== 'undefined') {
                        textarea.markItUp(markitup_settings[barre]);
                    }
                }
            });
        }
    });
})(jQuery);
