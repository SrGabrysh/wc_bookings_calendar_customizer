<?php
declare( strict_types=1 );

namespace TBWeb\WCBookingsCustomizer\Core;

use TBWeb\WCBookingsCustomizer\Modules\Calendar\CalendarManager;
use TBWeb\WCBookingsCustomizer\Modules\Admin\AdminManager;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Classe principale du plugin - Orchestration pure
 * 
 * Responsabilité unique : Initialiser et orchestrer les modules
 * Max 250 lignes selon les règles
 */
class Plugin {
    
    private static $instance = null;
    private $logger;
    private $modules = array();
    
    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_core();
        $this->check_dependencies();
        $this->init_modules();
        $this->init_admin();
    }
    
    /**
     * Initialisation du core
     */
    private function init_core() {
        $this->logger = new Logger();
    }
    
    /**
     * Vérification des dépendances
     */
    private function check_dependencies() {
        if ( ! $this->has_required_plugins() ) {
            add_action( 'admin_notices', array( $this, 'dependency_notice' ) );
            return false;
        }
        return true;
    }
    
    /**
     * Initialisation des modules
     */
    private function init_modules() {
        if ( ! $this->has_required_plugins() ) {
            return;
        }
        
        // Module Calendar - responsabilité unique
        $this->modules['calendar'] = new CalendarManager( $this->logger );
    }
    
    /**
     * Initialisation de l'admin
     */
    private function init_admin() {
        if ( is_admin() && $this->has_required_plugins() ) {
            $this->modules['admin'] = new AdminManager( $this->logger, $this->modules );
        }
    }
    
    /**
     * Vérifier les plugins requis
     */
    private function has_required_plugins() {
        return class_exists( 'WooCommerce' ) && class_exists( 'WC_Bookings' );
    }
    
    /**
     * Notice de dépendance
     */
    public function dependency_notice() {
        ?>
        <div class="notice notice-error">
            <p>
                <strong>WooCommerce Bookings Calendar Customizer</strong> nécessite 
                <strong>WooCommerce</strong> et <strong>WooCommerce Bookings</strong> 
                pour fonctionner.
            </p>
        </div>
        <?php
    }
    
    /**
     * Activation du plugin
     */
    public static function activate() {
        add_option( 'wc_bookings_customizer_settings', array(
            'style' => 'gcal',
            'version' => WC_BOOKINGS_CUSTOMIZER_VERSION
        ) );
        flush_rewrite_rules();
    }
    
    /**
     * Désactivation du plugin
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }
    
    /**
     * Obtenir un module
     */
    public function get_module( $name ) {
        return isset( $this->modules[ $name ] ) ? $this->modules[ $name ] : null;
    }
}
