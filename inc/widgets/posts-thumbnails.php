<?php
/**
 * Recent Posts w/ Thumbnails
 *
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 */

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
    exit;
}

// Start widget class
if ( ! class_exists( 'WPEX_Recent_Posts_Thumb_Widget' ) ) {
	class WPEX_Recent_Posts_Thumb_Widget extends WP_Widget {
		
		/**
		 * Register widget with WordPress.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			parent::__construct(
				'wpex_recent_posts_thumb',
				$name = __( 'Posts With Thumbnails', 'wpex-zero' ),
				array(
					'description'	=> __( 'Recent posts with thumbnails.', 'wpex-zero' ),
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

			// Extract args
			extract( $args );

			// Args
			$title			= isset( $instance['title'] ) ? $instance['title'] : '';
			$title			= apply_filters( 'widget_title', $title );
			$number			= isset( $instance['number'] ) ? $instance['number'] : '';
			$order			= isset( $instance['order'] ) ? $instance['order'] : 'DESC';
			$orderby		= isset( $instance['orderby'] ) ? $instance['orderby'] : 'date';
			$category		= isset( $instance['category'] ) ? $instance['category'] : 'all';
			$excerpt_length	= isset( $instance['excerpt_length'] ) ? $instance['excerpt_length'] : '10';

			// Exclude current post
			if ( is_singular() ) {
				$exclude = array( get_the_ID() );
			} else {
				$exclude = NULL;
			}

			// Before widget hook
			echo $before_widget;

			// Display widget title
			if ( $title ) {
				// Title
				echo $before_title . $title . $after_title;
			}
			// Category
			if ( ! empty( $category ) && 'all' != $category ) {
				$taxonomy = array (
					array (
						'taxonomy'	=> 'category',
						'field'		=> 'id',
						'terms'		=> $category,
					)
				);
			} else {
				$taxonomy = NUll;
			}

			// Query Posts
			global $post;
			$wpex_query = new WP_Query( array(
				'post_type'				=> 'post',
				'posts_per_page'		=> $number,
				'orderby'				=> $orderby,
				'order'					=> $order,
				'no_found_rows'			=> true,
				'meta_key'				=> '_thumbnail_id',
				'post__not_in'			=> $exclude,
				'tax_query'				=> $taxonomy,
				'ignore_sticky_posts'	=> 1
			) );

			// Loop through posts
			if ( $wpex_query->have_posts() ) { ?>

				<ul class="wpex-widget-recent-posts widget-recent-list clr">
					<?php foreach( $wpex_query->posts as $post ) : setup_postdata( $post );
						if ( has_post_thumbnail() ) { ?>
							<li>
								<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" class="thumbnail clr">
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
					<?php endforeach; ?>
				</ul>
			<?php }

			// Reset post data
			wp_reset_postdata();

			// After widget hook
			echo $after_widget;
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
			$instance					= $old_instance;
			$instance['title']			= strip_tags( $new_instance['title'] );
			$instance['number']			= strip_tags( $new_instance['number'] );
			$instance['order']			= strip_tags( $new_instance['order'] );
			$instance['orderby']		= strip_tags( $new_instance['orderby'] );
			$instance['category']		= strip_tags( $new_instance['category'] );
			$instance['excerpt_length']	= strip_tags( $new_instance['excerpt_length'] );
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
			$instance = wp_parse_args( ( array ) $instance, array(
				'title'				=> __( 'Recent Posts','wpex-zero' ),
				'number'			=> '5',
				'order'				=> 'DESC',
				'orderby'			=> 'date',
				'date'				=> '',
				'category'			=> 'all',
				'excerpt_length'	=> '10',

			) );
			$title			= esc_attr( $instance['title'] );
			$number			= esc_attr( $instance['number'] );
			$order			= esc_attr( $instance['order'] );
			$orderby		= esc_attr( $instance['orderby'] );
			$category		= esc_attr( $instance['category'] );
			$excerpt_length	= esc_attr( $instance['excerpt_length'] ); ?>
			
			
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpex-zero' ); ?>:</label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title','wpex-zero' ); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number to Show', 'wpex-zero' ); ?>:</label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>"><?php _e( 'Excerpt Length', 'wpex-zero' ); ?>:</label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" type="text" value="<?php echo $excerpt_length; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order', 'wpex-zero' ); ?>:</label>
				<br />
				<select class='wpex-select' name="<?php echo $this->get_field_name( 'order' ); ?>" id="<?php echo $this->get_field_id( 'order' ); ?>">
				<option value="DESC" <?php if( $order == 'DESC' ) { ?>selected="selected"<?php } ?>><?php _e( 'Descending', 'wpex-zero' ); ?></option>
				<option value="ASC" <?php if( $order == 'ASC' ) { ?>selected="selected"<?php } ?>><?php _e( 'Ascending', 'wpex-zero' ); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Order By', 'wpex-zero' ); ?>:</label>
				<br />
				<select class='wpex-select' name="<?php echo $this->get_field_name( 'orderby' ); ?>" id="<?php echo $this->get_field_id( 'orderby' ); ?>">
				<?php $orderby_array = array (
					'date'			=> __( 'Date', 'wpex-zero' ),
					'title'			=> __( 'Title', 'wpex-zero' ),
					'modified'		=> __( 'Modified', 'wpex-zero' ),
					'author'		=> __( 'Author', 'wpex-zero' ),
					'rand'			=> __( 'Random', 'wpex-zero' ),
					'comment_count'	=> __( 'Comment Count', 'wpex-zero' ),
				);
				foreach ( $orderby_array as $key => $value ) { ?>
					<option value="<?php echo $key; ?>" <?php if( $orderby == $key ) { ?>selected="selected"<?php } ?>>
						<?php echo $value; ?>
					</option>
				<?php } ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category', 'wpex-zero' ); ?>:</label>
				<br />
				<select class='wpex-select' name="<?php echo $this->get_field_name( 'category' ); ?>" id="<?php echo $this->get_field_id( 'category' ); ?>">
				<option value="all" <?php if($category == 'all' ) { ?>selected="selected"<?php } ?>><?php _e( 'All', 'wpex-zero' ); ?></option>
				<?php
				$terms = get_terms( 'category' );
				foreach ( $terms as $term ) { ?>
					<option value="<?php echo $term->term_id; ?>" <?php if( $category == $term->term_id ) { ?>selected="selected"<?php } ?>><?php echo $term->name; ?></option>
				<?php } ?>
				</select>
			</p>
			<?php
		}
	}
}

// Register the WPEX_Tabs_Widget custom widget
if ( ! function_exists( 'wpex_register_recet_posts_thumb_widget' ) ) {
	function wpex_register_recet_posts_thumb_widget() {
		register_widget( 'WPEX_Recent_Posts_Thumb_Widget' );
	}
}
add_action( 'widgets_init', 'wpex_register_recet_posts_thumb_widget' );