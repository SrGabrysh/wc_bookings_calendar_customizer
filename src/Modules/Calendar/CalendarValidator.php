<?php
declare( strict_types=1 );

namespace TBWeb\WCBookingsCustomizer\Modules\Calendar;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Validator du module Calendar - Validation uniquement
 * 
 * Responsabilité unique : Validation des paramètres du calendrier
 * Max 200 lignes selon les règles
 */
class CalendarValidator {
    
    private $logger;
    private $allowed_styles;
    
    public function __construct( $logger ) {
        $this->logger = $logger;
        $this->allowed_styles = array( 'gcal', 'material', 'minimalist', 'glassmorphism', 'modern-sidebar' );
    }
    
    /**
     * Valider les paramètres du calendrier
     */
    public function validate_settings( $settings ) {
        $validated = array();
        $errors = array();
        
        // Validation du style
        if ( isset( $settings['style'] ) ) {
            $style_validation = $this->validate_style( $settings['style'] );
            if ( $style_validation['valid'] ) {
                $validated['style'] = $style_validation['value'];
            } else {
                $errors['style'] = $style_validation['error'];
            }
        }
        
        // Log des erreurs
        if ( ! empty( $errors ) ) {
            $this->logger->error( 'Erreurs de validation des paramètres', $errors );
        }
        
        return array(
            'validated' => $validated,
            'errors' => $errors,
            'valid' => empty( $errors )
        );
    }
    
    /**
     * Valider le style sélectionné
     */
    private function validate_style( $style ) {
        $style = sanitize_text_field( $style );
        
        if ( empty( $style ) ) {
            return array(
                'valid' => false,
                'error' => 'Le style ne peut pas être vide',
                'value' => null
            );
        }
        
        if ( ! in_array( $style, $this->allowed_styles, true ) ) {
            return array(
                'valid' => false,
                'error' => 'Style non autorisé : ' . $style,
                'value' => null
            );
        }
        
        return array(
            'valid' => true,
            'error' => null,
            'value' => $style
        );
    }
    
    /**
     * Obtenir la liste des styles autorisés
     */
    public function get_allowed_styles() {
        return $this->allowed_styles;
    }
    
    /**
     * Vérifier si un style est autorisé
     */
    public function is_style_allowed( $style ) {
        return in_array( $style, $this->allowed_styles, true );
    }
}
