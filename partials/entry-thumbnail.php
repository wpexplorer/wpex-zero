<?php
/**
 * Displays the entry thumbnail
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
if ( ! get_theme_mod( 'entry_thumbnail', true ) ) {
	return;
} ?>

<div class="loop-entry-thumbnail">
	<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>">
		<?php the_post_thumbnail( 'entry' ); ?>
	</a>
</div><!-- .loop-entry-thumbnail -->