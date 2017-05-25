<?php
/**
 * Displays the post thumbnail
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
if ( ! get_theme_mod( 'post_thumbnail', true ) ) {
	return;
} ?>

<?php if ( has_post_thumbnail() ) : ?>
	<div class="post-thumbnail clr">
		<?php the_post_thumbnail( 'post' ); ?>
	</div><!-- .post-thumbnail -->
<?php endif; ?>