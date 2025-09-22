<?php
/**
 * Fichier de debug pour WooCommerce Bookings Calendar Customizer
 * À supprimer après debug
 */

// Ajouter des logs de debug avancés
add_action( 'wp_footer', function() {
    if ( is_product() ) {
        echo '<!-- WC Bookings Customizer Debug -->';
        echo '<script>console.log("WC Bookings Customizer: Plugin chargé sur page produit");</script>';
        
        // Vérifier si les styles sont chargés
        global $wp_styles, $wp_scripts;
        if ( isset( $wp_styles->registered['wc-bookings-customizer-style'] ) ) {
            echo '<script>console.log("WC Bookings Customizer: Style CSS chargé", ' . wp_json_encode($wp_styles->registered['wc-bookings-customizer-style']) . ');</script>';
        } else {
            echo '<script>console.log("WC Bookings Customizer: Style CSS NON chargé");</script>';
        }
        
        // Vérifier si les scripts sont chargés
        if ( isset( $wp_scripts->registered['wc-bookings-customizer-script'] ) ) {
            echo '<script>console.log("WC Bookings Customizer: Script JS chargé");</script>';
        } else {
            echo '<script>console.log("WC Bookings Customizer: Script JS NON chargé");</script>';
        }
        
        // Vérifier les options
        $settings = get_option( 'wc_bookings_customizer_settings', array() );
        echo '<script>console.log("WC Bookings Customizer Settings:", ' . wp_json_encode( $settings ) . ');</script>';
        
        // Vérifier la présence des classes
        echo '<script>
            console.log("WC Bookings Customizer: Classes disponibles:");
            console.log("- TBWeb\\\\WCBookingsCustomizer\\\\Core\\\\Plugin:", ' . (class_exists('TBWeb\\WCBookingsCustomizer\\Core\\Plugin') ? 'true' : 'false') . ');
            console.log("- WooCommerce:", ' . (class_exists('WooCommerce') ? 'true' : 'false') . ');
            console.log("- WC_Bookings:", ' . (class_exists('WC_Bookings') ? 'true' : 'false') . ');
        </script>';
        
        // Vérifier les styles WooCommerce Bookings
        echo '<script>
            console.log("WC Bookings Styles natifs:");
            console.log("- wc-bookings-styles:", ' . (isset($wp_styles->registered['wc-bookings-styles']) ? 'true' : 'false') . ');
            console.log("- jquery-ui-style:", ' . (isset($wp_styles->registered['jquery-ui-style']) ? 'true' : 'false') . ');
        </script>';
    }
});

// Forcer l'affichage des erreurs PHP pour debug
add_action( 'wp_footer', function() {
    if ( current_user_can( 'administrator' ) && is_product() ) {
        echo '<script>console.log("WC Bookings Customizer: Mode debug administrateur activé");</script>';
    }
});
?>
