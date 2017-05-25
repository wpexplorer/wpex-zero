<?php
/**
 * The default template for displaying post entries.
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

// Add extra entry classes
$classes = array( 'loop-entry' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<?php get_template_part( 'partials/entry', 'media' ); ?>
	<div class="loop-entry-content clr">
		<?php get_template_part( 'partials/entry', 'title' ); ?>
		<?php get_template_part( 'partials/entry', 'content' ); ?>
		<?php get_template_part( 'partials/entry', 'meta' ); ?>
	</div><!-- .loop-entry-content -->
</article><!-- .loop-entry -->
