<?php
/**
 * Fichier de debug pour WooCommerce Bookings Calendar Customizer
 * À supprimer après debug
 */

// Ajouter des logs de debug
add_action( 'wp_footer', function() {
    if ( is_product() ) {
        echo '<!-- WC Bookings Customizer Debug -->';
        echo '<script>console.log("WC Bookings Customizer: Plugin chargé sur page produit");</script>';
        
        // Vérifier si les styles sont chargés
        global $wp_styles;
        if ( isset( $wp_styles->registered['wc-bookings-customizer-style'] ) ) {
            echo '<script>console.log("WC Bookings Customizer: Style CSS chargé");</script>';
        } else {
            echo '<script>console.log("WC Bookings Customizer: Style CSS NON chargé");</script>';
        }
        
        // Vérifier les options
        $settings = get_option( 'wc_bookings_customizer_settings', array() );
        echo '<script>console.log("WC Bookings Customizer Settings:", ' . wp_json_encode( $settings ) . ');</script>';
    }
});

// Forcer l'affichage des erreurs PHP pour debug
add_action( 'wp_footer', function() {
    if ( current_user_can( 'administrator' ) && is_product() ) {
        echo '<script>console.log("WC Bookings Customizer: Mode debug administrateur activé");</script>';
    }
});
?>
