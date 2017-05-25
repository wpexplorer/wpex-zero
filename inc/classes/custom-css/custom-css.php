<?php
/**
 * Creates the admin panel for adding custom CSS to your site
 * and outputs the custom CSS in the front-end
 *
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @version     1.0.0
 * @link        https://github.com/wpexplorer/wordpress-custom-css
 */

// Start Class
if ( ! class_exists( 'WPEX_Custom_CSS' ) ) {
    class WPEX_Custom_CSS {

        /**
         * Check if the class should run or not
         *
         * @since 1.0.0
         *
         * @var bool
         */
        public $run_class = true;

        /**
         * Returns the path to the class
         *
         * @since 1.0.0
         *
         * @var string
         */
        public $dir_uri = '';

        /**
         * Start things up
         */
        public function __construct() {

            // Check if class should run or not, good for disabling via child themes
            $run_class = apply_filters( 'wpex_custom_css_init_class', $this->run_class );
            if ( ! $run_class ) {
                return;
            }
            $this->dir_uri  = get_template_directory_uri() .'/inc/classes/custom-css/';
            $this->dir_uri  = apply_filters( 'wpex_custom_css_dir_uri', $this->dir_uri );

            // Add admin submenu page
            add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );

            // Register admin settings
            add_action( 'admin_init', array( $this,'register_settings' ) );

            // Load required scripts
            add_action( 'admin_enqueue_scripts',array( $this,'scripts' ) );

            // Display notices on admin save
            add_action( 'admin_notices', array( $this, 'notices' ) );

            // Outputs the custom CSS on the front end
            add_action( 'wp_head' , array( $this, 'output_css' ) );

        }

        /**
         * Add sub menu page for the custom CSS input
         *
         * @link http://codex.wordpress.org/Function_Reference/add_theme_page
         */
        public function add_submenu_page() {
            add_submenu_page(
                'themes.php',
                __( 'Custom CSS', 'wpex-zero' ),
                __( 'Custom CSS', 'wpex-zero' ),
                'administrator',
                'wpex-custom-css',
                array( $this, 'create_admin_page' )
            );
        }

        /**
         * Load scripts
         *
         * @link http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
         */
        public function scripts( $hook ) {

            // Only load scripts when needed
            if ( 'appearance_page_wpex-custom-css' != $hook ) {
                return;
            }

            // Assets directory
            $dir = $this->dir_uri .'assets/codemirror/';

            // Load JS files and required CSS
            wp_enqueue_script(
                'wpex-codemirror',
                $dir .
                'codemirror.js',
                array( 'jquery' )
            );
            wp_enqueue_script(
                'wpex-codemirror-css',
                $dir . 'css.js',
                array(
                    'jquery',
                    'wpex-codemirror'
                )
            );
            wp_enqueue_script(
                'wpex-codemirror-css-link',
                $dir . 'css-lint.js',
                array(
                    'jquery',
                    'wpex-codemirror',
                    'wpex-codemirror-css'
                )
            );
            wp_enqueue_style(
                'wpex-codemirror',
                $dir . 'codemirror.css'
            );

            // Load correct skin type based on theme option
            if ( 'dark' == get_option( 'wpex_custom_css_theme', 'dark' ) ) {
                wp_enqueue_style( 'wpex-codemirror-theme', $dir . 'theme-dark.css' );
            } else {
                wp_enqueue_style( 'wpex-codemirror-theme', $dir . 'theme-light.css' );
            }
            
        }

        /**
         * Register a setting and its sanitization callback.
         *
         * @link http://codex.wordpress.org/Function_Reference/register_setting
         */
        public function register_settings() {
            register_setting( 'wpex_custom_css', 'wpex_custom_css', array( $this, 'sanitize' ) );
            register_setting( 'wpex_custom_css', 'wpex_custom_css_theme' );
        }

        /**
         * Displays all messages registered to 'wpex-custom_css-notices'
         *
         * @link http://codex.wordpress.org/Function_Reference/settings_errors
         */
        public function notices() {
            settings_errors( 'wpex_custom_css_notices' );
        }

        /**
         * Sanitization callback
         */
        public function sanitize( $option ) {

            // Set option to theme mod
            set_theme_mod( 'wpex_custom_css', $option );

            // Return notice
            add_settings_error(
                'wpex_custom_css_notices',
                esc_attr( 'settings_updated' ),
                __( 'Settings saved.', 'wpex-zero' ),
                'updated'
            );

            // Lets save the custom CSS into a standard option as well for backup
            return $option;
        }

        /**
         * Settings page output
         */
        public function create_admin_page() { ?>
            <div class="wrap">
                <h2 style="padding-right:0;">
                    <?php _e( 'Custom CSS', 'wpex-zero' ); ?>
                </h2>
                <p><?php _e( 'Use the form below to add custom CSS to tweak your theme design. These will be stored in your theme_mods.', 'wpex-zero' ); ?></p>
                <div style="margin:10px 0 20px;"><a href="#" class="button-secondary wpex-custom-css-toggle-skin"><?php _e( 'Toggle Skin', 'wpex-zero' ); ?></a></div>
                <form method="post" action="options.php">
                    <?php settings_fields( 'wpex_custom_css' ); ?>
                    <table class="form-table">
                        <tr valign="top">
                            <td style="padding:0;">
                                <textarea rows="40" cols="50" id="wpex_custom_css" style="width:100%;" name="wpex_custom_css"><?php echo get_theme_mod( 'wpex_custom_css', false ); ?></textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="wpex_custom_css_theme" value="<?php echo get_option( 'wpex_custom_css_theme', 'dark' ); ?>" id="wpex-default-codemirror-theme"></input>
                    <?php submit_button(); ?>
                </form>
            </div>
            <script>
                ( function( $ ) {
                    "use strict";
                    window.onload = function() {
                        window.editor = CodeMirror.fromTextArea(wpex_custom_css, {
                            mode         : "css",
                            lineNumbers  : true,
                            lineWrapping : true,
                            theme        : 'wpex-zero',
                            lint         : true
                        } );
                    };
                    <?php
                    // Get assets directory
                    $dir = $this->dir_uri .'assets/codemirror/'; ?>
                    $( '.wpex-custom-css-toggle-skin' ).click(function() {
                        var hiddenField = $( '#wpex-default-codemirror-theme' );
                        if ( hiddenField.val() == 'dark' ) {
                            hiddenField.val( 'light' );
                            $( '#wpex-codemirror-theme-css' ).attr( 'href','<?php echo esc_url( $dir ); ?>theme-light.css' );
                        } else {
                            hiddenField.val( 'dark' );
                            $( '#wpex-codemirror-theme-css' ).attr( 'href','<?php echo esc_url( $dir ); ?>theme-dark.css' );
                        }
                        return false;
                    } );
                } ) ( jQuery );
            </script>
        <?php }

        /**
         * Minifies the css output
         */
        public function minify_css( $css ) {

            // Normalize whitespace
            $css = preg_replace( '/\s+/', ' ', $css );

            // Remove ; before }
            $css = preg_replace( '/;(?=\s*})/', '', $css );

            // Remove space after , : ; { } */ >
            $css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );

            // Remove space before , ; { }
            $css = preg_replace( '/ (,|;|\{|})/', '$1', $css );

            // Strips leading 0 on decimal values (converts 0.5px into .5px)
            $css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );

            // Strips units if value is 0 (converts 0px to 0)
            $css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

            // Return minified CSS
            return trim( $css );

        }

        /**
         * Outputs the custom CSS to the wp_head
         */
        public function output_css( $output ) {
            $css = get_theme_mod( 'wpex_custom_css', false );
            $css = $this->minify_css( $css );
            if ( ! empty( $css ) ) {
                echo '<!-- Theme Custom CSS --><style>'. $css .'</style>';
            }
        }
    }
}
new WPEX_Custom_CSS();