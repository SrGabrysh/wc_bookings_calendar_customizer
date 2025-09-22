<?php
declare( strict_types=1 );

namespace TBWeb\WCBookingsCustomizer\Modules\Admin;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Ajax Handler du module Admin - Gestion AJAX uniquement
 * 
 * Responsabilité unique : Traitement des requêtes AJAX admin
 * Max 200 lignes selon les règles
 */
class AdminAjaxHandler {
    
    private $logger;
    private $modules;
    
    public function __construct( $logger, $modules ) {
        $this->logger = $logger;
        $this->modules = $modules;
        
        $this->init_ajax_hooks();
    }
    
    /**
     * Initialisation des hooks AJAX
     */
    private function init_ajax_hooks() {
        add_action( 'wp_ajax_wc_bookings_customizer_preview_style', array( $this, 'handle_preview_style' ) );
        add_action( 'wp_ajax_wc_bookings_customizer_reset_settings', array( $this, 'handle_reset_settings' ) );
    }
    
    /**
     * Gérer la prévisualisation de style
     */
    public function handle_preview_style() {
        // Vérification de sécurité
        if ( ! $this->verify_ajax_request() ) {
            wp_die( 'Accès refusé' );
        }
        
        $style = sanitize_text_field( $_POST['style'] ?? '' );
        
        if ( ! isset( $this->modules['calendar'] ) ) {
            wp_send_json_error( 'Module calendrier non disponible' );
        }
        
        $validator = $this->modules['calendar']->get_validator();
        if ( ! $validator->is_style_allowed( $style ) ) {
            wp_send_json_error( 'Style non autorisé' );
        }
        
        // Simulation de la prévisualisation
        $preview_data = array(
            'style' => $style,
            'css_url' => WC_BOOKINGS_CUSTOMIZER_URL . 'assets/css/style-' . $style . '.css',
            'preview_available' => true
        );
        
        $this->logger->debug( 'Prévisualisation de style demandée', array( 'style' => $style ) );
        
        wp_send_json_success( $preview_data );
    }
    
    /**
     * Gérer la réinitialisation des paramètres
     */
    public function handle_reset_settings() {
        // Vérification de sécurité
        if ( ! $this->verify_ajax_request() ) {
            wp_die( 'Accès refusé' );
        }
        
        $default_settings = array(
            'style' => 'gcal',
            'version' => WC_BOOKINGS_CUSTOMIZER_VERSION
        );
        
        update_option( 'wc_bookings_customizer_settings', $default_settings );
        
        $this->logger->info( 'Paramètres réinitialisés aux valeurs par défaut' );
        
        wp_send_json_success( array(
            'message' => 'Paramètres réinitialisés avec succès',
            'redirect_url' => admin_url( 'admin.php?page=wc-bookings-calendar-customizer' )
        ) );
    }
    
    /**
     * Vérifier la requête AJAX
     */
    private function verify_ajax_request() {
        return current_user_can( 'manage_woocommerce' ) && 
               check_ajax_referer( 'wc_bookings_customizer_nonce', 'nonce', false );
    }
}
