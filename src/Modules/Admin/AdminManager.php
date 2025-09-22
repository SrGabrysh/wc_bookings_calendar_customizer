<?php
declare( strict_types=1 );

namespace TBWeb\WCBookingsCustomizer\Modules\Admin;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Manager du module Admin - Orchestration uniquement
 * 
 * Responsabilité unique : Orchestrer les composants admin
 * Max 250 lignes selon les règles
 */
class AdminManager {
    
    private $logger;
    private $modules;
    private $renderer;
    private $ajax_handler;
    
    public function __construct( $logger, $modules ) {
        $this->logger = $logger;
        $this->modules = $modules;
        $this->renderer = new AdminRenderer( $logger );
        $this->ajax_handler = new AdminAjaxHandler( $logger, $modules );
        
        $this->init_hooks();
    }
    
    /**
     * Initialisation des hooks admin
     */
    private function init_hooks() {
        add_action( 'admin_menu', array( $this, 'add_menu' ) );
        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        
        $this->logger->debug( 'Admin Manager initialisé' );
    }
    
    /**
     * Ajouter le menu admin
     */
    public function add_menu() {
        add_submenu_page(
            'woocommerce',
            'Bookings Calendar Customizer',
            'Calendar Customizer',
            'manage_woocommerce',
            'wc-bookings-calendar-customizer',
            array( $this->renderer, 'render_main_page' )
        );
    }
    
    /**
     * Initialisation des paramètres admin
     */
    public function admin_init() {
        register_setting( 
            'wc_bookings_customizer_settings_group', 
            'wc_bookings_customizer_settings',
            array( $this, 'sanitize_settings' )
        );
        
        add_settings_section(
            'wc_bookings_customizer_main_section',
            'Paramètres du Calendrier',
            array( $this->renderer, 'render_section_description' ),
            'wc_bookings_customizer_settings'
        );
        
        add_settings_field(
            'style_selection',
            'Style du Calendrier',
            array( $this->renderer, 'render_style_field' ),
            'wc_bookings_customizer_settings',
            'wc_bookings_customizer_main_section'
        );
    }
    
    /**
     * Charger les assets admin
     */
    public function enqueue_admin_assets( $hook ) {
        if ( strpos( $hook, 'wc-bookings-calendar-customizer' ) === false ) {
            return;
        }
        
        wp_enqueue_style(
            'wc-bookings-customizer-admin',
            WC_BOOKINGS_CUSTOMIZER_URL . 'assets/css/admin.css',
            array(),
            WC_BOOKINGS_CUSTOMIZER_VERSION
        );
        
        wp_enqueue_script(
            'wc-bookings-customizer-admin',
            WC_BOOKINGS_CUSTOMIZER_URL . 'assets/js/admin.js',
            array( 'jquery' ),
            WC_BOOKINGS_CUSTOMIZER_VERSION,
            true
        );
    }
    
    /**
     * Sanitiser les paramètres
     */
    public function sanitize_settings( $input ) {
        if ( ! isset( $this->modules['calendar'] ) ) {
            return $input;
        }
        
        $validator = $this->modules['calendar']->get_validator();
        $validation_result = $validator->validate_settings( $input );
        
        if ( ! $validation_result['valid'] ) {
            add_settings_error(
                'wc_bookings_customizer_settings',
                'validation_error',
                'Erreurs de validation : ' . implode( ', ', $validation_result['errors'] )
            );
            return get_option( 'wc_bookings_customizer_settings', array() );
        }
        
        return $validation_result['validated'];
    }
}
