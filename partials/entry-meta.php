<?php
/**
 * Used to output entry meta info - date, category, comments, author...etc
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

// Return if disabled
if ( ! get_theme_mod( 'entry_meta', true ) ) {
	return;
}

// Get post data
$post_id	= get_the_ID();
$post_type	= get_post_type( $post_id );

// Get items to display
$meta_items = array( 'date', 'category', 'comments' );
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
} ?>

<div class="entry-meta clr">
	<ul class="clr">
		<?php foreach ( $meta_items as $meta_item ) : ?>
			<?php if ( 'date' == $meta_item ) { ?>
			<li class="date"><?php _e( 'Posted on', 'wpex-zero' ); ?> <span><?php echo get_the_date(); ?></span></li>
			<?php } ?>
			<?php if ( 'category' == $meta_item && isset( $terms ) ) : ?>
				<li class="categories"><?php _e( 'in', 'wpex-zero' ); ?> <?php echo $terms; ?></li>
			<?php endif; ?>
			<?php if ( 'comments' == $meta_item && comments_open() && wpex_are_comments_enabled() ) { ?>
				<li class="comments comment-scroll"><?php _e( 'with', 'wpex-zero' ); ?> <?php comments_popup_link( __( '0 Comments', 'wpex-zero' ), __( '1 Comment',  'wpex-zero' ), __( '% Comments', 'wpex-zero' ), 'comments-link' ); ?></li>
			<?php } ?>
		<?php endforeach; ?>
	</ul>
	<?php get_template_part( 'partials/entry', 'readmore' ); ?>
</div><!-- .entry-meta -->