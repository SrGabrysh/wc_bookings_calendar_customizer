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
        
        wp_dequeue_style( 'wc-bookings-styles' );
        wp_dequeue_style( 'jquery-ui-style' );
        wp_dequeue_style( 'wc-bookings-jquery-ui-styles' );
        
        $this->logger->debug( 'Styles natifs désactivés' );
    }
    
    /**
     * Charger les styles CSS
     */
    private function enqueue_styles() {
        wp_enqueue_style(
            'wc-bookings-customizer-style',
            WC_BOOKINGS_CUSTOMIZER_URL . 'assets/css/style-' . $this->selected_style . '.css',
            array(),
            WC_BOOKINGS_CUSTOMIZER_VERSION
        );
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
}
