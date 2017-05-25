<?php
/**
 * Outputs the main post layout
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
} ?>

<article class="post-article clr">
	<?php get_template_part( 'partials/post', 'media' ); ?>
	<?php get_template_part( 'partials/post', 'header' ); ?>
	<?php get_template_part( 'partials/post', 'content' ); ?>
	<?php get_template_part( 'partials/post-link', 'pages' ); ?>
	<div class="entry-footer clr">
		<?php get_template_part( 'partials/post', 'edit' ); ?>
		<?php get_template_part( 'partials/post', 'meta' ); ?>
		<?php get_template_part( 'partials/post', 'tags' ); ?>
	</div><!-- .entry-footer -->
	<?php get_template_part( 'partials/post', 'share' ); ?>
	<?php get_template_part( 'partials/post', 'author' ); ?>
	<?php comments_template(); ?>
	<?php get_template_part( 'partials/post', 'navigation' ); ?>
</article>