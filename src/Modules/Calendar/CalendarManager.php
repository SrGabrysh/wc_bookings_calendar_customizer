<?php
declare( strict_types=1 );

namespace TBWeb\WCBookingsCustomizer\Modules\Calendar;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Manager du module Calendar - Orchestration uniquement
 * 
 * Responsabilité unique : Orchestrer les composants du calendrier
 * Max 250 lignes selon les règles
 */
class CalendarManager {
    
    private $logger;
    private $handler;
    private $validator;
    
    public function __construct( $logger ) {
        $this->logger = $logger;
        $this->handler = new CalendarHandler( $logger );
        $this->validator = new CalendarValidator( $logger );
        
        $this->init_hooks();
    }
    
    /**
     * Initialisation des hooks - responsabilité du manager
     */
    private function init_hooks() {
        // Hooks pour les scripts et styles - priorités ajustées
        add_action( 'wp_enqueue_scripts', array( $this->handler, 'dequeue_native_styles' ), 5 );
        add_action( 'wp_enqueue_scripts', array( $this->handler, 'enqueue_assets' ), 999 );
        
        // Hook pour la validation
        add_filter( 'wc_bookings_customizer_validate_settings', array( $this->validator, 'validate_settings' ) );
        
        $this->logger->debug( 'Calendar Manager initialisé' );
    }
    
    /**
     * Obtenir le handler
     */
    public function get_handler() {
        return $this->handler;
    }
    
    /**
     * Obtenir le validator
     */
    public function get_validator() {
        return $this->validator;
    }
    
    /**
     * Vérifier si le module est actif sur la page courante
     */
    public function is_active_on_current_page() {
        return is_product() || is_cart() || is_checkout();
    }
}
