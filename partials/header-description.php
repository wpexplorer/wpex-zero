<?php
/**
 * Displays the site description for the header
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

// Check for the existence of the description
if ( $description = get_bloginfo( 'description' ) ) : ?>

	<div class="site-description clr">
		<?php echo $description; ?>
	</div><!-- .site-description -->

<?php endif; ?>