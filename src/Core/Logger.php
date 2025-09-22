<?php
declare( strict_types=1 );

namespace TBWeb\WCBookingsCustomizer\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Logger simple pour le plugin
 * 
 * Responsabilité unique : Gestion des logs
 * Max 200 lignes selon les règles
 */
class Logger {
    
    private $log_level;
    
    public function __construct() {
        $this->log_level = defined( 'WP_DEBUG' ) && WP_DEBUG ? 'debug' : 'error';
    }
    
    /**
     * Log d'information
     */
    public function info( $message, $context = array() ) {
        $this->log( 'info', $message, $context );
    }
    
    /**
     * Log d'erreur
     */
    public function error( $message, $context = array() ) {
        $this->log( 'error', $message, $context );
    }
    
    /**
     * Log de debug
     */
    public function debug( $message, $context = array() ) {
        if ( $this->log_level === 'debug' ) {
            $this->log( 'debug', $message, $context );
        }
    }
    
    /**
     * Écriture du log
     */
    private function log( $level, $message, $context ) {
        if ( ! function_exists( 'wc_get_logger' ) ) {
            return;
        }
        
        $logger = wc_get_logger();
        $context_string = ! empty( $context ) ? wp_json_encode( $context ) : '';
        $full_message = $message . ( $context_string ? ' | Context: ' . $context_string : '' );
        
        $logger->log( $level, $full_message, array( 'source' => 'wc-bookings-customizer' ) );
    }
}
