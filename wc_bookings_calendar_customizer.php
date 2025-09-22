<?php
/**
 * Plugin Name: WooCommerce Bookings Calendar Customizer
 * Plugin URI: https://tb-web.fr
 * Description: Personnalise l'apparence du calendrier WooCommerce Bookings avec le style Google Calendar
 * Version: 1.3.0
 * Author: TB-Web
 * License: GPL v2 or later
 * Text Domain: wc-bookings-calendar-customizer
 * Requires at least: 5.0
 * Tested up to: 6.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) exit;

// Test de base : vérifier que le plugin se charge
add_action( 'wp_footer', function() {
    if ( is_product() ) {
        echo '<script>console.log("🔧 WC Bookings Customizer: Fichier principal chargé !");</script>';
    }
});

// Constantes
define( 'WC_BOOKINGS_CUSTOMIZER_VERSION', '1.3.0' );
define( 'WC_BOOKINGS_CUSTOMIZER_PATH', plugin_dir_path( __FILE__ ) );
define( 'WC_BOOKINGS_CUSTOMIZER_URL', plugin_dir_url( __FILE__ ) );

// Autoload
require_once WC_BOOKINGS_CUSTOMIZER_PATH . 'vendor/autoload.php';

// Hooks activation
register_activation_hook( __FILE__, array( TBWeb\WCBookingsCustomizer\Core\Plugin::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( TBWeb\WCBookingsCustomizer\Core\Plugin::class, 'deactivate' ) );

// Debug temporaire
if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
    include_once WC_BOOKINGS_CUSTOMIZER_PATH . 'debug.php';
    include_once WC_BOOKINGS_CUSTOMIZER_PATH . 'test-plugin-loading.php';
}

// Initialisation
add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'wc-bookings-calendar-customizer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    TBWeb\WCBookingsCustomizer\Core\Plugin::instance();
} );
