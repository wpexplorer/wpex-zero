<?php
/**
 * Used to output post meta info - date, category, comments, author...etc
 *
 * @package		Zero
 * @subpackage	Partials
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Return if password protected
if ( post_password_required() ) {
	return;
} 

// Return if disabled
if ( ! get_theme_mod( 'post_meta', true ) ) {
	return;
}

// Get post data
$post_id	= get_the_ID();
$post_type	= get_post_type( $post_id );

// Get items to display
$meta_items = array( 'date', 'category' );
$meta_items	= array_combine( $meta_items, $meta_items );

// You can tweak the meta output via a function, yay!
$meta_items = apply_filters( 'wpex_meta_items', $meta_items );

// Get taxonomy for the posted under section
if ( 'post' == $post_type ) {
	$taxonomy = 'category';
} else {
	$taxonomy = NULL;
}

// Get terms
if ( $taxonomy ) {
	$terms = wpex_list_post_terms( $taxonomy, false );
} else {
	$terms = NULL;
}

// Date text
if ( is_attachment() ) {
	$date_text = __( 'Uploaded on', 'wpex-zero' );
} else {
	$date_text = __( 'Posted on', 'wpex-zero' );
} ?>

<ul class="post-meta clr">
	<?php foreach ( $meta_items as $meta_item ) : ?>
		<?php if ( 'date' == $meta_item ) { ?>
		<li class="meta-date">
			<?php echo $date_text; ?> <a href="<?php the_permalink(); ?>" title="<?php _e( 'Post link', 'wpex-zero' ); ?>"><?php echo get_the_date(); ?></a>
		</li>
		<?php } ?>
		<?php if ( 'category' == $meta_item && isset( $terms ) ) : ?>
			<li class="meta-category">
				<?php _e( 'in', 'wpex-zero' ); ?> <?php echo $terms; ?>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul><!-- .post-meta -->