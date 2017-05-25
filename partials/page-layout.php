<?php
/**
 * Returns the page layout components
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

<article class="page-article clr">
	<?php get_template_part( 'partials/page', 'thumbnail' ); ?>
	<?php get_template_part( 'partials/page', 'header' ); ?>
	<?php get_template_part( 'partials/page', 'content' ); ?>
	<div class="entry-footer clr">
		<?php get_template_part( 'partials/post', 'edit' ); ?>
	</div><!-- .entry-footer -->
	<?php comments_template(); ?>
</article><!-- .page-article -->