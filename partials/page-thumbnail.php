<?php
/**
 * Displays the page thumbnail
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

<?php if ( has_post_thumbnail() ) : ?>
	<div class="page-thumbnail clr">
		<?php the_post_thumbnail( 'post' ); ?>
	</div><!-- .page-thumbnail -->
<?php endif; ?>