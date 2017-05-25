<?php
/**
 * Displays the next/previous post links
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

// Not needed here
if ( is_attachment() || post_password_required() ) {
	return;
} ?>


<?php if ( function_exists( 'the_post_navigation' ) ) : ?>
	<?php
	// Define arguments
	$args = array(
		'prev_text'	=> '%title <span class="fa fa-arrow-right"></span>',
		'next_text'	=> '<span class="fa fa-arrow-left"></span> %title',
	);
	// Output pagination
	the_post_navigation( $args ); ?>
<?php endif; ?>