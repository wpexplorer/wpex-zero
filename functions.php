<?php
/**
 * Theme functions and definitions.
 *
 * Sets up the theme and provides some helper functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 *
 * For more information on hooks, actions, and filters,
 * see http://codex.wordpress.org/Plugin_API
 *
 * @package     Zero
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @since       1.0.0
 */

/**
 * Define content_width variable
 *
 * @since 1.0.0
 */ 
if ( ! isset( $content_width ) ) {
    $content_width = 745;
}

/**
 * Main Theme Class => One class to rule it all!
 *
 * @since 1.0.0
 */
class WPEX_THEME_SETUP {

    /**
     * Start things up
     *
     * @since 1.0.0
     */
    public function __construct() {

        // Paths
        $this->template_dir     = get_template_directory();
        $this->template_dir_uri = get_template_directory_uri();
        $this->classes_dir      = $this->template_dir .'/inc/classes/';
        $this->css_dir_uri      = $this->template_dir_uri .'/css/';
        $this->js_dir_uri       = $this->template_dir_uri .'/js/';

        // Auto updates
        if ( is_admin() ) {
            require_once( $this->template_dir .'/inc/updates.php' );
        }

        // Include functions and classes
        add_action( 'init', array( $this, 'load_files' ) );

        // Remove locale_stylesheet from wp_head
        add_action( 'init', array( $this, 'remove_locale_stylesheet' ) );

        // Perform basic setup, registration, and init actions for a theme.
        add_action( 'after_setup_theme', array( $this, 'setup' ) );

        // Define post types for the gallery metabox
        add_filter( 'wpex_gallery_metabox_post_types', array( $this, 'gallery_metabox_post_types' ) );

        // Enque Front-End scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'front_end_scripts' ) );

        // Register custom widget areas
        add_action( 'widgets_init', array( $this, 'register_sidebars' ) );

        // Add classes to the body tag
        add_action( 'body_class', array( $this, 'body_classes' ) );

        // Add classes to the post_class function
        add_action( 'post_class', array( $this, 'post_classes' ) );

        // Alter default read_more
        add_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );

        // Add responsive wrapper around oembeds
        add_filter( 'embed_oembed_html', array( $this, 'embed_oembed_html' ), 99, 4 );

        // Alter the default posts per page for custom post type archives
        add_filter( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

        // Add thumbnails to post admin dashboard
        add_filter( 'manage_post_posts_columns', array( $this, 'posts_columns' ), 10 );
        add_action( 'manage_posts_custom_column', array( $this, 'posts_custom_columns' ), 10, 2 );
        add_filter( 'manage_page_posts_columns', array( $this, 'posts_columns' ), 10 );
        add_action( 'manage_pages_custom_column', array( $this, 'posts_custom_columns' ), 10, 2 );

        // Retina logo
        add_action( 'wp_head', array( $this, 'retina_logo' ) );

        // Custom Widgets
        require_once( $this->template_dir .'/inc/widgets/social.php' );
        require_once( $this->template_dir .'/inc/widgets/tabs.php' );
        require_once( $this->template_dir .'/inc/widgets/posts-thumbnails.php' );

        // Add excerpt support to pages
        add_post_type_support( 'page', 'excerpt' );

        // Add font size select to mce
        add_filter( 'mce_buttons_2', array( $this, 'mce_font_size_select' ) );

        // Customize mce editor font sizes
        add_filter( 'tiny_mce_before_init', array( $this, 'fontsize_formats' ) );

        // Add TinyMCE formats
        add_filter( 'mce_buttons', array( $this, 'formats_button' ) );
        add_filter( 'tiny_mce_before_init', array( $this, 'formats' ) );

        // Remove default gallery styles
        add_filter( 'use_default_gallery_style', array( $this, 'remove_gallery_styles' ) );

    }

    /**
     * Include functions and classes
     *
     * @since 1.0.0
     */
    public function load_files() {

        // Include theme functions
        require_once( $this->template_dir .'/inc/core-functions.php' );
        require_once( $this->template_dir .'/inc/conditionals.php' );
        require_once( $this->template_dir .'/inc/customizer-config.php' );
        require_once( $this->template_dir .'/inc/meta-config.php' );

        // Include Classes
        require_once ( $this->classes_dir .'custom-css/custom-css.php' );
        require_once ( $this->classes_dir .'customizer/customizer.php' );
        require_once ( $this->classes_dir .'gallery-metabox/gallery-metabox.php' );
        require_once ( $this->classes_dir .'custom-metaboxes/init.php' );

    }

    /**
     * Remove locale_stylesheet so we can load it prior to responsive.css
     *
     * @since 1.0.0
     */
    public function remove_locale_stylesheet() {
        remove_action( 'wp_head', 'locale_stylesheet' );
    }

    /**
     * Functions called during each page load, after the theme is initialized
     * Perform basic setup, registration, and init actions for the theme
     *
     * @link    http://codex.wordpress.org/Plugin_API/Action_Reference/after_setup_theme
     * @since   1.0.0
     */
    public function setup() {

        // Register navigation menus
        register_nav_menus (
            array(
                'primary'   => __( 'Primary', 'wpex-zero' ),
            )
        );

        // Add editor styles
        add_editor_style( 'css/editor-style.css' );
        
        // Localization support
        load_theme_textdomain( 'wpex-zero', get_template_directory() .'/languages' );
            
        // Add theme support
        add_theme_support( 'title-tag' );
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'custom-background' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-header' );

        // Add image sizes
        add_image_size(
            'entry',
            get_theme_mod( 'entry_thumbnail_width', 940 ),
            get_theme_mod( 'entry_thumbnail_height', 480 ),
            get_theme_mod( 'entry_thumbnail_crop', true )
        );
        add_image_size(
            'post',
            get_theme_mod( 'post_thumbnail_width', 940 ),
            get_theme_mod( 'post_thumbnail_height', 480 ),
            get_theme_mod( 'post_thumbnail_crop', true )
        );


    }

    /**
     * Define post types for the gallery metabox
     *
     * @since 1.0.0
     */
    public function gallery_metabox_post_types( $post_types ) {
        $post_types = array( 'post' );
        return $post_types;
    }

    /**
     * Load custom scripts in the front end
     *
     * @since 1.0.0
     */
    public function front_end_scripts() {
        $this->theme_css();
        $this->theme_js();
    }

    /**
     * Load custom CSS scripts in the front end
     *
     * @since 1.0.0
     */
    public function theme_css() {

        // Main CSS
        wp_enqueue_style( 'style', get_stylesheet_uri() );

        // RTL CSS
        if ( is_RTL() ) {
            wp_enqueue_style( 'wpex-rtl', $this->css_dir_uri .'rtl.css' );
        }

        // Responsive CSS
        if ( get_theme_mod( 'responsive', true ) ) {
            wp_enqueue_style( 'wpex-responsive', $this->css_dir_uri .'responsive.css' );
        }

        // Font Awesome
        wp_enqueue_style( 'wpex-font-awesome', $this->css_dir_uri .'font-awesome.min.css', 'style' );

        // Remove Contact Form 7 Styles
        if ( function_exists( 'wpcf7_enqueue_styles') ) {
            wp_dequeue_style( 'contact-form-7' );
        }

    }

    /**
     * Load custom JS scripts in the front end
     *
     * @since 1.0.0
     */
    public function theme_js() {

        // Comment reply
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

        // jQuery Plugins
        wp_enqueue_script( 'images-loaded', $this->js_dir_uri .'images-loaded.js', array( 'jquery' ), '3.0.4', true );
        wp_enqueue_script( 'light-slider', $this->js_dir_uri .'light-slider.js', array( 'jquery' ), '1.1.0', true );
        wp_enqueue_script( 'magnific-popup', $this->js_dir_uri .'magnific-popup.js', array( 'jquery' ), '0.9.9', true );
        wp_enqueue_script( 'match-height', $this->js_dir_uri .'match-height.js', array( 'jquery' ), '0.5.2', true );

        // Theme functions
        wp_enqueue_script( 'wpex-functions', $this->js_dir_uri .'functions.js', array( 'jquery' ), false, true );

        // Localize scripts
        $localize_array = array(
            'isMobile'          => wp_is_mobile(),
            'isRTL'             => is_rtl(),
            'wpGalleryLightbox' => true,
        );
        $localize_array = apply_filters( 'wpex_localize_array', $localize_array );
        wp_localize_script( 'wpex-functions', 'wpexLocalize', $localize_array );

    }

    /**
     * Registers the theme sidebars
     *
     * @link    http://codex.wordpress.org/Function_Reference/register_sidebar
     * @since   1.0.0
     */
    function register_sidebars() {

        // Sidebar
        register_sidebar( array(
            'name'          => __( 'Sidebar', 'wpex-zero' ),
            'id'            => 'sidebar',
            'description'   => __( 'Widgets in this area are used in the sidebar region.', 'wpex-zero' ),
            'before_widget' => '<div class="sidebar-widget %2$s clr">',
            'after_widget'  => '</div>',
            'before_title'  => '<h5 class="widget-title">',
            'after_title'   => '</h5>',
        ) );

    }
    
    /**
     * Adds classes to the body_class function
     *
     * @link    http://codex.wordpress.org/Function_Reference/body_class
     * @since   1.0.0
     */
    public function body_classes( $classes ) {
        if ( is_singular() && has_post_thumbnail() ) {
            $classes[] = 'has-thumbnail';
        }
        if ( is_home() && has_post_thumbnail( get_option( 'page_for_posts' ) ) ) {
            $classes[] = 'has-thumbnail';
        }
        return $classes;
    }
    
    /**
     * Adds classes to the post_class function
     *
     * @link    http://codex.wordpress.org/Function_Reference/post_class
     * @since   1.0.0
     */
    public function post_classes( $classes ) {

        // Clear floats
        $classes[] = 'clr';

        // No featured image
        if ( ! has_post_thumbnail() ) {
            $classes[] = 'no-thumbnail';
        }

        // Return classes
        return $classes;

    }

    /**
     * Return custom excerpt more string
     *
     * @link    http://codex.wordpress.org/Plugin_API/Filter_Reference/excerpt_more
     * @since   1.0.0
     */
    public function excerpt_more( $more ) {
        global $post;
        return '&hellip;';
    }

    /**
     * Alters the default oembed output
     *
     * @since   1.0.0
     * @link    https://developer.wordpress.org/reference/hooks/embed_oembed_html/
     */
    function embed_oembed_html( $html, $url, $attr, $post_id ) {
        return '<div class="responsive-embed">' . $html . '</div>';
    }

    /**
     * Alter the main query
     *
     * @link    http://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
     * @since   1.0.0
     */
    public function pre_get_posts( $query ) {

        // Return in admin
        if ( is_admin() ) {
            return;
        }

        // Return if not main query
        if ( ! $query->is_main_query() ) {
            return;
        }

        // Display only posts for search results
        if ( is_search() ) {
           $query->set('post_type', array( 'post' ) );
        }

    }

    /**
     * Adds new "Featured Image" column to the WP dashboard
     *
     * @link    http://codex.wordpress.org/Plugin_API/Filter_Reference/manage_posts_columns
     * @since   1.0.0
     */
    public function posts_columns( $defaults ) {
        $defaults['wpex_post_thumbs'] = __( 'Featured Image', 'wpex-zero' );
        return $defaults;
    }

    /**
     * Display post thumbnails in WP admin
     *
     * @link    http://codex.wordpress.org/Plugin_API/Filter_Reference/manage_posts_columns
     * @since   1.0.0
     */
    public function posts_custom_columns( $column_name, $id ) {
        $id = get_the_ID();
        if( $column_name != 'wpex_post_thumbs' ) {
            return;
        }
        if ( has_post_thumbnail( $id ) ) {
            $img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'thumbnail', false );
            if( ! empty( $img_src[0] ) ) { ?>
                    <img src="<?php echo esc_url( $img_src[0] ); ?>" alt="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" style="max-width:100%;max-height:90px;" />
                <?php
            }
        } else {
            echo '&mdash;';
        }
    }

    /**
     * Adds js for the retina logo
     *
     * @since 1.0.0
     */
    function retina_logo() {
        $logo_url       = get_theme_mod( 'logo_img_retina' );
        $logo_url		= wpex_sanitize_data( $logo_url, 'url' ); // Sanitize data, see @ inc/core-functions.php
        $logo_height    = get_theme_mod( 'logo_img_height' );
        if ( $logo_url && $logo_height) {
            $output = '<!-- Retina Logo --><script type="text/javascript">jQuery(function($){if (window.devicePixelRatio == 2) {$("#site-logo img").attr("src", "'. $logo_url .'");$("#site-logo img").css("height", "'. intval( $logo_height ) .'");}});</script>';
            echo $output;
        }
    }

    /**
     * Add Font size select to tinymce
     *
     * @link    http://codex.wordpress.org/Plugin_API/Filter_Reference/mce_buttons,_mce_buttons_2,_mce_buttons_3,_mce_buttons_4
     * @since   1.0.0
     */
    public function mce_font_size_select( $buttons ) {
        array_unshift( $buttons, 'fontsizeselect' );
        return $buttons;
    }
    
    /**
     * Customize default font size selections for the tinymce
     *
     * @link    http://codex.wordpress.org/Plugin_API/Filter_Reference/mce_buttons,_mce_buttons_2,_mce_buttons_3,_mce_buttons_4
     * @since   1.0.0
     */
    public function fontsize_formats( $initArray ) {
        $initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px";
        return $initArray;
    }

    /**
     * Add Formats Button
     *
     * @link    http://codex.wordpress.org/Plugin_API/Filter_Reference/mce_buttons,_mce_buttons_2,_mce_buttons_3,_mce_buttons_4
     * @since   1.0.0
     */
    function formats_button( $buttons ) {
        array_push( $buttons, 'styleselect' );
        return $buttons;
    }

    /**
     * Add new formats
     *
     * @link    http://codex.wordpress.org/TinyMCE_Custom_Styles
     * @since   1.0.0
     */
    public function formats( $settings ) {
        $new_formats = array(
            array(
                'title'     => __( 'Highlight', 'wpex-zero' ),
                'inline'    => 'span',
                'classes'   => 'text-highlight'
            ),
            array(
                'title' => __( 'Buttons', 'wpex-zero' ),
                'items' => array(
                    array(
                        'title'     => __( 'Default', 'wpex-zero' ),
                        'selector'  => 'a',
                        'classes'   => 'theme-button'
                    ),
                    array(
                        'title'     => __( 'Red', 'wpex-zero' ),
                        'selector'  => 'a',
                        'classes'   => 'theme-button red'
                    ),
                    array(
                        'title'     => __( 'Green', 'wpex-zero' ),
                        'selector'  => 'a',
                        'classes'   => 'theme-button green'
                    ),
                    array(
                        'title'     => __( 'Blue', 'wpex-zero' ),
                        'selector'  => 'a',
                        'classes'   => 'theme-button blue'
                    ),
                    array(
                        'title'     => __( 'Orange', 'wpex-zero' ),
                        'selector'  => 'a',
                        'classes'   => 'theme-button orange'
                    ),
                    array(
                        'title'     => __( 'Black', 'wpex-zero' ),
                        'selector'  => 'a',
                        'classes'   => 'theme-button black'
                    ),
                    array(
                        'title'     => __( 'White', 'wpex-zero' ),
                        'selector'  => 'a',
                        'classes'   => 'theme-button white'
                    ),
                    array(
                        'title'     => __( 'Clean', 'wpex-zero' ),
                        'selector'  => 'a',
                        'classes'   => 'theme-button clean'
                    ),
                ),
            ),
            array(
                'title' => __( 'Notices', 'wpex-zero' ),
                'items' => array(
                    array(
                        'title'     => __( 'Default', 'wpex-zero' ),
                        'block'     => 'div',
                        'classes'   => 'notice'
                    ),
                    array(
                        'title'     => __( 'Info', 'wpex-zero' ),
                        'block'     => 'div',
                        'classes'   => 'notice info'
                    ),
                    array(
                        'title'     => __( 'Warning', 'wpex-zero' ),
                        'block'     => 'div',
                        'classes'   => 'notice warning'
                    ),
                    array(
                        'title'     => __( 'Success', 'wpex-zero' ),
                        'block'     => 'div',
                        'classes'   => 'notice success'
                    ),
                ),
            ),
        );
        $settings['style_formats'] = json_encode( $new_formats );
        return $settings;
    }

    /**
     * Remove gallery styles
     *
     * @link    https://developer.wordpress.org/reference/hooks/use_default_gallery_style/
     * @since   1.0.0
     */
    public function remove_gallery_styles() {
        return false;
    }

}

// Start up class with variable for child theming
$wpex_theme_setup = new WPEX_THEME_SETUP;
