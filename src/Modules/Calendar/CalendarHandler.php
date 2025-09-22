<?php
declare( strict_types=1 );

namespace TBWeb\WCBookingsCustomizer\Modules\Calendar;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handler du module Calendar - Logique métier uniquement
 * 
 * Responsabilité unique : Traitement des assets et customisation du calendrier
 * Max 250 lignes selon les règles
 */
class CalendarHandler {
    
    private $logger;
    private $selected_style;
    
    public function __construct( $logger ) {
        $this->logger = $logger;
        $this->selected_style = $this->get_selected_style();
    }
    
    /**
     * Charger les assets du calendrier
     */
    public function enqueue_assets() {
        if ( ! $this->should_enqueue_assets() ) {
            return;
        }
        
        $this->enqueue_styles();
        $this->enqueue_scripts();
        
        $this->logger->debug( 'Assets du calendrier chargés', array( 'style' => $this->selected_style ) );
    }
    
    /**
     * Désactiver les styles natifs
     */
    public function dequeue_native_styles() {
        if ( ! $this->should_enqueue_assets() ) {
            return;
        }
        
        // Désactiver tous les styles WooCommerce Bookings avec deregister pour être sûr
        wp_dequeue_style( 'wc-bookings-styles' );
        wp_deregister_style( 'wc-bookings-styles' );
        wp_dequeue_style( 'wc_bookings_styles' );
        wp_deregister_style( 'wc_bookings_styles' );
        wp_dequeue_style( 'jquery-ui-style' );
        wp_deregister_style( 'jquery-ui-style' );
        wp_dequeue_style( 'wc-bookings-jquery-ui-styles' );
        wp_deregister_style( 'wc-bookings-jquery-ui-styles' );
        wp_dequeue_style( 'jquery-ui-datepicker' );
        wp_deregister_style( 'jquery-ui-datepicker' );
        wp_dequeue_style( 'jquery-ui-theme' );
        wp_deregister_style( 'jquery-ui-theme' );
        
        // Désactiver également les scripts qui pourraient interférer
        wp_dequeue_script( 'wc-bookings-date-picker' );
        wp_dequeue_script( 'wc-bookings-time-picker' );
        
        $this->logger->debug( 'Styles natifs désactivés avec deregister' );
    }
    
    /**
     * Charger les styles CSS
     */
    private function enqueue_styles() {
        wp_enqueue_style(
            'wc-bookings-customizer-style',
            WC_BOOKINGS_CUSTOMIZER_URL . 'assets/css/style-' . $this->selected_style . '.css',
            array(),
            WC_BOOKINGS_CUSTOMIZER_VERSION . '-' . time() // Cache busting pour debug
        );
        
        // Forcer la priorité CSS avec du CSS inline pour override complet
        wp_add_inline_style( 'wc-bookings-customizer-style', '
            /* Force Google Calendar Style Priority - Override tout */
            .wc-bookings-booking-form,
            .wc-bookings-booking-form *,
            #wc-bookings-booking-form,
            #wc-bookings-booking-form *,
            .wc-bookings-date-picker,
            .picker {
                font-family: "Google Sans", "Product Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            }
            
            /* Forcer la visibilité du calendrier personnalisé */
            .wc-bookings-booking-form {
                display: block !important;
                visibility: visible !important;
            }
            
            /* CSS CRITIQUE : Forcer les dimensions du conteneur pour Google Calendar */
            #wc-bookings-booking-form,
            .wc-bookings-booking-form {
                width: 450px !important;
                min-width: 450px !important;
                max-width: none !important;
                overflow: visible !important;
                position: relative !important;
                z-index: 1000 !important;
            }
            
            /* Forcer l\'élargissement des conteneurs parents */
            .single-product .product .summary,
            .woocommerce-page .product .summary {
                min-width: 500px !important;
                overflow: visible !important;
            }
        ' );
    }
    
    /**
     * Charger les scripts JS
     */
    private function enqueue_scripts() {
        wp_enqueue_script(
            'wc-bookings-customizer-script',
            WC_BOOKINGS_CUSTOMIZER_URL . 'assets/js/calendar-modern.js',
            array( 'jquery' ),
            WC_BOOKINGS_CUSTOMIZER_VERSION,
            true
        );
        
        // Variables pour JavaScript
        wp_localize_script( 'wc-bookings-customizer-script', 'wcBookingsCustomizer', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'wc_bookings_customizer_nonce' ),
            'style' => $this->selected_style
        ) );
    }
    
    /**
     * Vérifier si on doit charger les assets
     */
    private function should_enqueue_assets() {
        return is_product() || is_cart() || is_checkout();
    }
    
    /**
     * Obtenir le style sélectionné
     */
    private function get_selected_style() {
        $settings = get_option( 'wc_bookings_customizer_settings', array() );
        return isset( $settings['style'] ) ? $settings['style'] : 'gcal';
    }
    
    /**
     * Mettre à jour le style
     */
    public function update_style( $new_style ) {
        $settings = get_option( 'wc_bookings_customizer_settings', array() );
        $settings['style'] = $new_style;
        update_option( 'wc_bookings_customizer_settings', $settings );
        
        $this->selected_style = $new_style;
        $this->logger->info( 'Style mis à jour', array( 'nouveau_style' => $new_style ) );
    }
    
    /**
     * Forcer l'override des styles en dernier recours
     */
    public function force_style_override() {
        if ( ! $this->should_enqueue_assets() ) {
            return;
        }
        
        echo '<style id="wc-bookings-customizer-force-override">
            /* Force override des styles WooCommerce Bookings */
            .wc-bookings-booking-form,
            #wc-bookings-booking-form {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            /* Désactiver complètement les styles jQuery UI */
            .ui-widget-content,
            .ui-widget-header,
            .ui-state-default,
            .ui-corner-all {
                background: none !important;
                border: none !important;
                color: inherit !important;
            }
            
            /* Forcer la police Google */
            .wc-bookings-booking-form,
            .wc-bookings-booking-form *,
            .ui-datepicker,
            .ui-datepicker * {
                font-family: "Google Sans", "Product Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            }
        </style>';
        
        $this->logger->debug( 'Force override CSS injecté dans wp_head' );
    }
}
