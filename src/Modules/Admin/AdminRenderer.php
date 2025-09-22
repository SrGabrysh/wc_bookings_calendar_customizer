<?php
declare( strict_types=1 );

namespace TBWeb\WCBookingsCustomizer\Modules\Admin;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Renderer du module Admin - Affichage uniquement
 * 
 * Responsabilité unique : Rendu des pages et éléments admin
 * Max 250 lignes selon les règles
 */
class AdminRenderer {
    
    private $logger;
    private $styles_config;
    
    public function __construct( $logger ) {
        $this->logger = $logger;
        $this->styles_config = array(
            'gcal' => array(
                'name' => 'Google Calendar Inspired',
                'description' => 'Style inspiré de Google Calendar avec gradients et animations'
            ),
            'material' => array(
                'name' => 'Material Design',
                'description' => 'Design moderne selon les principes Material de Google'
            ),
            'minimalist' => array(
                'name' => 'Minimalist Modern',
                'description' => 'Interface épurée et moderne'
            ),
            'glassmorphism' => array(
                'name' => 'Glassmorphism',
                'description' => 'Effet de verre tendance avec transparence'
            ),
            'modern-sidebar' => array(
                'name' => 'Modern Sidebar Calendar',
                'description' => 'Calendrier avec sidebar vert et affichage moderne'
            )
        );
    }
    
    /**
     * Rendu de la page principale
     */
    public function render_main_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            
            <?php $this->render_info_notice(); ?>
            
            <form method="post" action="options.php">
                <?php
                settings_fields( 'wc_bookings_customizer_settings_group' );
                do_settings_sections( 'wc_bookings_customizer_settings' );
                submit_button( 'Enregistrer les modifications' );
                ?>
            </form>
            
            <?php $this->render_help_section(); ?>
        </div>
        <?php
    }
    
    /**
     * Rendu de la description de section
     */
    public function render_section_description() {
        echo '<p>Choisissez le style qui correspond le mieux à votre thème WordPress.</p>';
    }
    
    /**
     * Rendu du champ de sélection de style
     */
    public function render_style_field() {
        $current_settings = get_option( 'wc_bookings_customizer_settings', array() );
        $current_style = isset( $current_settings['style'] ) ? $current_settings['style'] : 'gcal';
        
        echo '<select name="wc_bookings_customizer_settings[style]" id="calendar_style">';
        
        foreach ( $this->styles_config as $style_key => $style_info ) {
            $selected = ( $current_style === $style_key ) ? 'selected' : '';
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr( $style_key ),
                $selected,
                esc_html( $style_info['name'] )
            );
        }
        
        echo '</select>';
        echo '<p class="description">Style actuellement sélectionné : <strong>' . esc_html( $this->styles_config[ $current_style ]['name'] ) . '</strong></p>';
    }
    
    /**
     * Notice d'information
     */
    private function render_info_notice() {
        ?>
        <div class="notice notice-info">
            <p><strong>ℹ️ Information :</strong> Ce plugin personnalise l'apparence du calendrier WooCommerce Bookings avec des styles modernes.</p>
        </div>
        <?php
    }
    
    /**
     * Section d'aide
     */
    private function render_help_section() {
        ?>
        <div class="notice notice-success" style="margin-top: 30px;">
            <h3>💡 Conseils d'utilisation</h3>
            <ul>
                <li><strong>Cache :</strong> Videz le cache de votre site après avoir changé de style.</li>
                <li><strong>Compatibilité :</strong> Testé avec WooCommerce 8.0+ et WooCommerce Bookings 1.15+</li>
                <li><strong>Support :</strong> Pour toute question, contactez TB-Web.</li>
            </ul>
        </div>
        <?php
    }
}
