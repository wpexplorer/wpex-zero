<?php
/**
 * Tabs Widget
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
if ( ! class_exists( 'WPEX_Tabs_Widget' ) ) {
    class WPEX_Tabs_Widget extends WP_Widget {

        /**
         * Register widget with WordPress.
         *
         * @since 1.0.0
         */
        function __construct() {
            parent::__construct(
                'wpex_tabs_widget',
                __( 'Tabs', 'wpex-zero' ),
                array(
                    'description' => __( 'Popular Posts, Recent Posts & Recent Comments.', 'wpex-zero' )
                )
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

            // Set vars
            $title      = isset( $instance['title'] ) ? $instance['title'] : '';
            $title      = apply_filters( 'widget_title', $title );
            $number     = isset( $instance['number'] ) ? $instance['number'] : '';

            // Get current ID to exclude it
            if ( is_singular() ) {
                $exclude = array( get_the_ID() );
            } else {
                $exclude = NULL;
            }

            // Before title hook
            echo $before_widget;

                // Display title
                if ( $title ) {
                    echo $before_title . $title . $after_title;
                } ?>

                <div class="wpex-tabs-widget clr">
                    <div class="wpex-tabs-widget-inner clr">
                        <ul class="wpex-tabs-widget-tabs clr">
                            <li class="active">
                                <a href="#" data-tab="#wpex-widget-recent-tab"><span class="fa fa-file-text"></span></a>
                            </li>
                            <li>
                                <a href="#" data-tab="#wpex-widget-comments-tab" class="last"><span class="fa fa-comments"></span></a>
                            </li>
                            <?php if ( $has_tags = get_terms( 'post_tag' ) ) { ?>
                                <li>
                                    <a href="#" data-tab="#wpex-widget-tags-tab" class="last"><span class="fa fa-tags"></span></a>
                                </li>
                            <?php } ?>
                        </ul><!-- .wpex-tabs-widget-tabs -->
                        <div id="wpex-widget-recent-tab" class="wpex-tabs-widget-tab active-tab clr">
                            <ul class="clr widget-recent-list">
                                <?php
                                // Query Posts
                                global $post;
                                $wpex_query = new WP_Query( array(
                                    'post_type'         => 'post',
                                    'posts_per_page'    => $number,
                                    'orderby'           => 'date',
                                    'no_found_rows'     => true,
                                    'meta_key'          => '_thumbnail_id',
                                    'post__not_in'      => $exclude,
                                ) );
                                if ( $wpex_query->have_posts() ) :
                                    foreach( $wpex_query->posts as $post ) : setup_postdata( $post );
                                    if ( has_post_thumbnail() ) { ?>
                                        <li class="clr">
                                            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" class="clr thumbnail">
                                                <?php the_post_thumbnail( 'thumbnail' ); ?>
                                            </a>
                                            <div class="details clr">
                                                <div class="title">
                                                    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>">
                                                        <?php the_title(); ?>
                                                    </a>
                                                </div><!-- .title -->
                                                <div class="date">
                                                    <?php echo get_the_date(); ?>
                                                </div><!-- .date -->
                                            </div><!-- .details -->
                                        </li>
                                    <?php } ?>
                                    <?php
                                    endforeach;
                                endif;
                                wp_reset_postdata(); ?>
                            </ul>
                        </div><!-- wpex-tabs-widget-tab -->
                        <div id="wpex-widget-comments-tab" class="wpex-tabs-widget-tab clr">
                            <ul class="clr widget-recent-list">
                                <?php
                                // Query Posts
                                $comments = get_comments( array (
                                    'number'        => $number,
                                    'status'        => 'approve',
                                    'post_status'   => 'publish',
                                    'type'          => 'comment',
                                ) );
                                if ( $comments ) {
                                    foreach ( $comments as $comment ) { ?>
                                        <li class="clr">
                                            <a href="<?php echo get_permalink( $comment->comment_post_ID ) . '#comment-' . $comment->comment_ID; ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" class="clr thumbnail">
                                                <?php echo get_avatar( $comment->comment_author_email, '100' ); ?>
                                            </a>
                                            <div class="excerpt">
                                                <a href="<?php echo get_permalink( $comment->comment_post_ID ) . '#comment-' . $comment->comment_ID; ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>"><?php echo get_comment_author( $comment->comment_ID ); ?>:</a> <?php echo wp_trim_words( $comment->comment_content, '10', '&hellip;' ); ?>
                                            </div>
                                        </li>
                                    <?php }
                                } else { ?>
                                    <li><?php _e( 'No comments yet.', 'wpex-zero' ); ?></li>
                                <?php } ?>
                            </ul>
                        </div><!-- .wpex-tabs-widget-tab -->
                        <?php if ( $has_tags ) { ?>
                            <div id="wpex-widget-tags-tab" class="wpex-tabs-widget-tab clr">
                                <?php
                                // Display tag cloud
                                wp_tag_cloud( array(
                                    'smallest'  => 14,
                                    'largest'   => 14,
                                    'unit'      => 'px',
                                    'number'    => 45,
                                    'format'    => 'flat',
                                ) ); ?>
                            </div><!-- .wpex-tabs-widget-tab -->
                        <?php } ?>
                    </div><!-- .wpex-tabs-widget-inner -->
                </div><!-- .wpex-tabs-widget -->
            <?php echo $after_widget;
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
        function update( $new_instance, $old_instance ) {

            $instance = $old_instance;

            // Title
            if ( ! empty( $new_instance['title'] ) ) {
                $instance['title'] = strip_tags( $new_instance['title'] );
            } else {
                $instance['title'] = '';
            }

            // Number
            if ( ! empty( $new_instance['number'] ) ) {
                $instance['number'] = strip_tags( $new_instance['number'] );
            } else {
                $instance['number'] = '';
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
        function form( $instance ) {

            $instance = wp_parse_args( ( array ) $instance, array(
                'title'  => '',
                'number' => '3',
            ));
            $title  = esc_attr( $instance['title'] );
            $number = esc_attr( $instance['number'] ); ?>
            
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'wpex-zero' ); ?></label> 
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title','wpex-zero' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number to Show:', 'wpex-zero' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" />
            </p>
            <?php
        }
    }
}

// Register the WPEX_Tabs_Widget custom widget
if ( ! function_exists( 'wpex_register_tabs_widget' ) ) {
    function wpex_register_tabs_widget() {
        register_widget( 'WPEX_Tabs_Widget' );
    }
}
add_action( 'widgets_init', 'wpex_register_tabs_widget' );