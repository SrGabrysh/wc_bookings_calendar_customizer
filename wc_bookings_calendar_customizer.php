<?php
/**
 * Plugin Name: WooCommerce Bookings Calendar Customizer
 * Plugin URI: https://tb-web.fr
 * Description: Personnalise l'apparence du calendrier WooCommerce Bookings avec le style Google Calendar
 * Version: 1.0.3
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
define( 'WC_BOOKINGS_CUSTOMIZER_VERSION', '1.0.3' );
define( 'WC_BOOKINGS_CUSTOMIZER_PATH', plugin_dir_path( __FILE__ ) );
define( 'WC_BOOKINGS_CUSTOMIZER_URL', plugin_dir_url( __FILE__ ) );

// Autoload avec vérification
if ( file_exists( WC_BOOKINGS_CUSTOMIZER_PATH . 'vendor/autoload.php' ) ) {
    require_once WC_BOOKINGS_CUSTOMIZER_PATH . 'vendor/autoload.php';
} else {
    // Fallback : chargement manuel des classes principales
    require_once WC_BOOKINGS_CUSTOMIZER_PATH . 'src/Core/Logger.php';
    require_once WC_BOOKINGS_CUSTOMIZER_PATH . 'src/Core/Plugin.php';
    require_once WC_BOOKINGS_CUSTOMIZER_PATH . 'src/Modules/Calendar/CalendarValidator.php';
    require_once WC_BOOKINGS_CUSTOMIZER_PATH . 'src/Modules/Calendar/CalendarHandler.php';
    require_once WC_BOOKINGS_CUSTOMIZER_PATH . 'src/Modules/Calendar/CalendarManager.php';
    require_once WC_BOOKINGS_CUSTOMIZER_PATH . 'src/Modules/Admin/AdminRenderer.php';
    require_once WC_BOOKINGS_CUSTOMIZER_PATH . 'src/Modules/Admin/AdminAjaxHandler.php';
    require_once WC_BOOKINGS_CUSTOMIZER_PATH . 'src/Modules/Admin/AdminManager.php';
}

// Hooks activation avec vérification de classe
register_activation_hook( __FILE__, function() {
    if ( class_exists( 'TBWeb\WCBookingsCustomizer\Core\Plugin' ) ) {
        TBWeb\WCBookingsCustomizer\Core\Plugin::activate();
    }
});

register_deactivation_hook( __FILE__, function() {
    if ( class_exists( 'TBWeb\WCBookingsCustomizer\Core\Plugin' ) ) {
        TBWeb\WCBookingsCustomizer\Core\Plugin::deactivate();
    }
});

// Debug temporaire
if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
    include_once WC_BOOKINGS_CUSTOMIZER_PATH . 'debug.php';
    include_once WC_BOOKINGS_CUSTOMIZER_PATH . 'test-plugin-loading.php';
}

// Initialisation avec gestion d'erreur
add_action( 'plugins_loaded', function() {
    try {
        load_plugin_textdomain( 'wc-bookings-calendar-customizer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        
        if ( class_exists( 'TBWeb\WCBookingsCustomizer\Core\Plugin' ) ) {
            TBWeb\WCBookingsCustomizer\Core\Plugin::instance();
        } else {
            add_action( 'admin_notices', function() {
                echo '<div class="notice notice-error"><p><strong>WC Bookings Calendar Customizer:</strong> Erreur de chargement des classes. Vérifiez que tous les fichiers sont présents.</p></div>';
            });
        }
    } catch ( Exception $e ) {
        add_action( 'admin_notices', function() use ( $e ) {
            echo '<div class="notice notice-error"><p><strong>WC Bookings Calendar Customizer:</strong> Erreur fatale - ' . esc_html( $e->getMessage() ) . '</p></div>';
        });
    }
} );
