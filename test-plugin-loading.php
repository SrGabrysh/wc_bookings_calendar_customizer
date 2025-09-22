<?php
/**
 * Test simple pour diagnostiquer le chargement du plugin
 * À supprimer après diagnostic
 */

// Test 1: Vérifier que ce fichier se charge
add_action( 'wp_footer', function() {
    echo '<script>console.log("TEST: Fichier test-plugin-loading.php chargé !");</script>';
});

// Test 2: Vérifier l'autoload
add_action( 'wp_footer', function() {
    echo '<script>console.log("TEST: Vérification autoload...");</script>';
    
    if (class_exists('TBWeb\\WCBookingsCustomizer\\Core\\Plugin')) {
        echo '<script>console.log("TEST: Classe Plugin trouvée ✅");</script>';
    } else {
        echo '<script>console.log("TEST: Classe Plugin NON trouvée ❌");</script>';
    }
});

// Test 3: Vérifier les dépendances
add_action( 'wp_footer', function() {
    echo '<script>console.log("TEST: Vérification dépendances...");</script>';
    echo '<script>console.log("- WooCommerce:", ' . (class_exists('WooCommerce') ? 'true' : 'false') . ');</script>';
    echo '<script>console.log("- WC_Bookings:", ' . (class_exists('WC_Bookings') ? 'true' : 'false') . ');</script>';
});

// Test 4: Vérifier les constantes
add_action( 'wp_footer', function() {
    echo '<script>console.log("TEST: Constantes plugin:");</script>';
    echo '<script>console.log("- WC_BOOKINGS_CUSTOMIZER_VERSION:", "' . (defined('WC_BOOKINGS_CUSTOMIZER_VERSION') ? WC_BOOKINGS_CUSTOMIZER_VERSION : 'NON DÉFINIE') . '");</script>';
    echo '<script>console.log("- WC_BOOKINGS_CUSTOMIZER_PATH:", "' . (defined('WC_BOOKINGS_CUSTOMIZER_PATH') ? WC_BOOKINGS_CUSTOMIZER_PATH : 'NON DÉFINIE') . '");</script>';
});

// Test 5: Forcer un message simple
add_action( 'wp_head', function() {
    echo '<style>body::before { content: "TEST PLUGIN LOADING ACTIF"; position: fixed; top: 0; left: 0; background: red; color: white; padding: 5px; z-index: 9999; }</style>';
});
?>
