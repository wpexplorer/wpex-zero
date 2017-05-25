<?php
/**
 * Font Awesome social widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API
 *
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 */

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
    exit;
}

// Start widget class
if ( ! class_exists( 'WPEX_Social_Profiles_Widget' ) ) {
    class WPEX_Social_Profiles_Widget extends WP_Widget {

        /**
         * Register widget with WordPress.
         *
         * @since 1.0.0
         */
        function __construct() {
            parent::__construct(
                'wpex_social_profiles_widget',
                __( 'Social Profiles', 'wpex-zero' ),
                array( 'description'   => __( 'Displays icons with links to your social profiles with drag and drop support and Font Awesome Icons.', 'wpex-zero' ) )
            );
        }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         * @since 1.0.0
         *
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget( $args, $instance ) {
            extract( $args );
            $title              = isset( $instance['title'] ) ? $instance['title'] : '';
            $title              = apply_filters( 'widget_title', $title );
            $description        = isset( $instance['description'] ) ? $instance['description'] : '';
            $target             = isset( $instance['target'] ) ? ' target="'. $instance['target'] .'"' : '';
            $size               = isset( $instance['size'] ) ? intval( $instance['size'] ) : '';
            $font_size          = isset( $instance['font_size'] ) ? intval( $instance['font_size'] ) : '';
            $border_radius      = isset( $instance['border_radius'] ) ? intval( $instance['border_radius'] ) : '';
            $social_services    = isset( $instance['social_services'] ) ? $instance['social_services'] : ''; ?>
            <?php echo $before_widget; ?>
                <?php if ( $title )
                    echo $before_title . $title . $after_title; ?>
                        <div class="wpex-social-profiles-widget clr">
                            <?php
                            // ADD Style
                            $add_style = array();
                            if ( $size ) {
                                $add_style[] = 'height:'. $size .'px;width:'. $size .'px;line-height:'. $size .'px;';
                            }
                            if ( $font_size ) {
                                $add_style[] = 'font-size:'. $font_size .'px;';
                            }
                            if ( $border_radius ) {
                                $add_style[] = 'border-radius:'. $border_radius .'px;';
                            }
                            $add_style = implode( '', $add_style);
                            if ( $add_style ) {
                                $add_style  = wp_kses( $add_style, array() );
                                $add_style  = 'style="' . esc_attr($add_style) . '"';
                            } ?>
                            <?php
                            // Description
                            if ( $description ) { ?>
                                <div class="desc clr">
                                    <?php echo wpex_sanitize_data( $description, 'html' ); ?>
                                </div>
                            <?php } ?>
                            <ul>
                                <?php
                                // Loop through each social service and display font icon
                                foreach( $social_services as $key => $service ) {
                                    $link   = ! empty( $service['url'] ) ? $service['url'] : null;
                                    $name   = $service['name'];
                                    // Display link
                                    if ( $link ) {
                                        if ( 'youtube' == $key ) {
                                            $key = 'youtube-play';
                                        } ?>
                                            <li class="<?php echo esc_attr( $key ); ?>">
                                                <a href="<?php echo esc_url( $link ); ?>" title="<?php echo esc_attr( $name ); ?>" <?php echo $add_style; ?><?php echo $target; ?>>
                                                    <span class="fa fa-<?php echo esc_attr( $key ); ?>"></span>
                                                </a>
                                            </li>
                                    <?php }
                                } ?>
                            </ul>
                        </div>
            <?php echo $after_widget; ?>
            <?php
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @see WP_Widget::update()
         * @since 1.0.0
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update( $new_instance, $old_instance ) {

            $instance = $old_instance;

            // Title
            if ( ! empty( $new_instance['title'] ) ) {
                $instance['title'] = strip_tags( $new_instance['title'] );
            } else {
                $instance['title'] = '';
            }

            // Description
            if ( ! empty( $new_instance['description'] ) ) {
                $instance['description'] = wp_kses( $new_instance['description'], array(
                        'a'         => array(
                            'href'  => array(),
                            'title' => array()
                        ),
                        'br'        => array(),
                        'em'        => array(),
                        'strong'    => array(),
                ) );
            } else {
                $instance['description'] = '';
            }

            // Target
            if ( ! empty( $new_instance['target'] ) ) {
                $instance['target'] = strip_tags( $new_instance['target'] );
            } else {
                $instance['target'] = 'blank';
            }

            // Size
            if ( ! empty( $new_instance['size'] ) ) {
                $instance['size'] = strip_tags( $new_instance['size'] );
            } else {
                $instance['size'] = '';
            }

            // Border Radius
            if ( ! empty( $new_instance['border_radius'] ) ) {
                $instance['border_radius'] = strip_tags( $new_instance['border_radius'] );
            } else {
                $instance['border_radius'] = '';
            }

            // Font Size
            if ( ! empty( $new_instance['font_size'] ) ) {
                $instance['font_size'] = strip_tags( $new_instance['font_size'] );
            } else {
                $instance['font_size'] = '';
            }

            // Social Services
            if ( ! empty( $new_instance['social_services'] ) ) {
                $instance['social_services'] = $new_instance['social_services'];
            } else {
                $instance['social_services'] = array();
            }

            return $instance;
        }

        /**
         * Back-end widget form.
         *
         * @see WP_Widget::form()
         * @since 1.0.0
         *
         * @param array $instance Previously saved values from database.
         */
        public function form( $instance ) {
            $defaults =  array(
                'title'             => __( 'Follow Us', 'wpex-zero' ),
                'description'       => '',
                'font_size'         => '14px',
                'border_radius'     => '4px',
                'target'            => 'blank',
                'size'              => '30px',
                'social_services'   => array(
                        'twitter'       => array(
                            'name'      => 'Twitter',
                            'url'       => ''
                        ),
                        'facebook'      => array(
                            'name'      => 'Facebook',
                            'url'       => ''
                        ),
                        'google-plus'   => array(
                            'name'      => 'GooglePlus',
                            'url'       => ''
                        ),
                        'instagram'     => array(
                            'name'      => 'Instagram',
                            'url'       => ''
                        ),
                        'linkedin'      => array(
                            'name'      => 'LinkedIn',
                            'url'       => ''
                        ),
                        'pinterest'     => array(
                            'name'      => 'Pinterest',
                            'url'       => ''
                        ),
                        'dribbble'      => array(
                            'name'      => 'Dribbble',
                            'url'       => ''
                        ),
                        'flickr'        => array(
                            'name'      => 'Flickr',
                            'url'       => ''
                        ),
                        'vimeo-square'  => array(
                            'name'      => 'Vimeo',
                            'url'       => ''
                        ),
                        'youtube'       => array(
                            'name'      => 'Youtube',
                            'url'       => ''
                        ),
                        'vk'            => array(
                            'name'      => 'VK',
                            'url'       => ''
                        ),
                        'github'        => array(
                            'name'      => 'GitHub',
                            'url'       => ''
                        ),
                        'tumblr'        => array(
                            'name'      => 'Tumblr',
                            'url'       => ''
                        ),
                        'skype'         => array(
                            'name'      => 'Skype',
                            'url'       => ''
                        ),
                        'trello'        => array(
                            'name'      => 'Trello',
                            'url'       => ''
                        ),
                        'foursquare'    => array(
                            'name'      => 'Foursquare',
                            'url'       => ''
                        ),
                        'renren'        => array(
                            'name'      => 'RenRen',
                            'url'       => ''
                        ),
                        'xing'          => array(
                            'name'      => 'Xing',
                            'url'       => ''
                        ),
                        'rss'           => array(
                            'name'      => 'RSS',
                            'url'       => ''
                        ),
                ),
            );
            $instance = wp_parse_args( ( array ) $instance, $defaults ); ?>
            
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'wpex-zero' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description:','wpex-zero' ); ?></label>
                <textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"><?php echo $instance['description']; ?></textarea>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'target' ); ?>"><?php _e( 'Link Target:', 'wpex-zero' ); ?></label>
                <br />
                <select class='wpex-widget-select' name="<?php echo $this->get_field_name( 'target' ); ?>" id="<?php echo $this->get_field_id( 'target' ); ?>">
                    <option value="blank" <?php if ( $instance['target'] == 'blank' ) { ?>selected="selected"<?php } ?>><?php _e( 'Blank', 'wpex-zero' ); ?></option>
                    <option value="self" <?php if ( $instance['target'] == 'self' ) { ?>selected="selected"<?php } ?>><?php _e( 'Self', 'wpex-zero' ); ?></option>
                </select>
            </p>
            
            <p>
                <label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Icon Size', 'wpex-zero' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>" type="text" value="<?php echo $instance['size']; ?>" />
                <small><?php _e( 'Enter a size to be used for the height/width for the icon.', 'wpex-zero' ); ?></small>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'font_size' ); ?>"><?php _e( 'Icon Font Size', 'wpex-zero' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'font_size' ); ?>" name="<?php echo $this->get_field_name( 'font_size' ); ?>" type="text" value="<?php echo $instance['font_size']; ?>" />
                <small><?php _e( 'Enter a custom font size for the icons.', 'wpex-zero' ); ?></small>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'border_radius' ); ?>"><?php _e( 'Border Radius', 'wpex-zero' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'border_radius' ); ?>" name="<?php echo $this->get_field_name( 'border_radius' ); ?>" type="text" value="<?php echo $instance['border_radius']; ?>" />
                <small><?php _e( 'Enter a custom border radius. For circular icons enter a number equal or greater to the Icon Size field above.', 'wpex-zero' ); ?></small>
            </p>

            <?php
            $field_id_services      = $this->get_field_id( 'social_services' );
            $field_name_services    = $this->get_field_name( 'social_services' ); ?>
            <h3 style="margin-top:20px;margin-bottom:0;"><?php _e( 'Social Links','wpex-zero' ); ?></h3>  
            <small style="display:block;margin-bottom:10px;"><?php _e( 'Enter the full URL to your social profile', 'wpex-zero' ); ?></small>
            <ul id="<?php echo $field_id_services; ?>" class="wpex-services-list">
                <input type="hidden" id="<?php echo $field_name_services; ?>" value="<?php echo $field_name_services; ?>">
                <input type="hidden" id="<?php echo wp_create_nonce( 'wpex_social_profiles_widget_nonce' ); ?>">
                <?php
                $display_services = isset ( $instance['social_services'] ) ? $instance['social_services']: '';
                if ( ! empty( $display_services ) ) {
                    foreach( $display_services as $key => $service ) {
                        $url        = isset( $service['url'] ) ? $service['url'] : 0;
                        $name       = isset( $service['name'] )  ? $service['name'] : ''; ?>
                        <li id="<?php echo $field_id_services; ?>_0<?php echo $key ?>">
                            <p>
                                <label for="<?php echo $field_id_services; ?>-<?php echo $key ?>-name"><?php echo $name; ?>:</label>
                                <input type="hidden" id="<?php echo $field_id_services; ?>-<?php echo $key ?>-url" name="<?php echo $field_name_services .'['.$key.'][name]'; ?>" value="<?php echo $name; ?>">
                                <input type="url" class="widefat" id="<?php echo $field_id_services; ?>-<?php echo $key ?>-url" name="<?php echo $field_name_services .'['.$key.'][url]'; ?>" value="<?php echo $url; ?>" />
                            </p>
                        </li>
                    <?php }
                } ?>
            </ul>
            
        <?php
        }
    }
}

// Register the WPEX_Tabs_Widget custom widget
if ( ! function_exists( 'wpex_register_social_profiles_widget' ) ) {
    function wpex_register_social_profiles_widget() {
        register_widget( 'WPEX_Social_Profiles_Widget' );
    }
}
add_action( 'widgets_init', 'wpex_register_social_profiles_widget' );


// Widget Styles
if ( ! function_exists( 'wpex_social_widget_style' ) ) {
    function wpex_social_widget_style() { ?>
        <style> 
        .wpex-services-list li {
            cursor: move;
            background: #fafafa;
            padding: 10px;
            border: 1px solid #e5e5e5;
            margin-bottom: 10px;
        }
        .wpex-services-list li p {
            margin: 0;
        }
        .wpex-services-list li label {
            margin-bottom: 3px;
            display: block;
            color: #222;
        }
        .wpex-services-list .placeholder {
            border: 1px dashed #e3e3e3;
        }
        </style>
    <?php
    }
}


// Widget AJAX functions
if ( ! function_exists( 'wpex_social_profiles_widget_scripts' ) ) {
    function wpex_social_profiles_widget_scripts() {
        global $pagenow;
        if ( is_admin() && $pagenow == "widgets.php" ) {
            add_action( 'admin_head', 'wpex_social_widget_style' );
            add_action( 'admin_footer', 'add_new_wpex_fontawesome_social_ajax_trigger' );
            function add_new_wpex_fontawesome_social_ajax_trigger() { ?>
                <script type="text/javascript" >
                    jQuery( document ).ready( function($) {
                        jQuery( document ).ajaxSuccess( function( e, xhr, settings ) {
                            var widget_id_base = 'wpex_social_profiles_widget';
                            if ( settings.data.search( 'action=save-widget' ) != -1 && settings.data.search( 'id_base=' + widget_id_base) != -1 ) {
                                wpexSortServices();
                            }
                        } );
                        function wpexSortServices() {
                            jQuery( '.wpex-services-list' ).each( function() {
                                var id = jQuery( this ).attr( 'id' );
                                $( '#'+ id ).sortable( {
                                    placeholder : "placeholder",
                                    opacity     : 0.6
                                } );
                            } );
                        }
                        wpexSortServices();
                    } );
                </script>
            <?php
            }
        }
    }
}
add_action( 'admin_init','wpex_social_profiles_widget_scripts' );