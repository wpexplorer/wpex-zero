<?php
/**
 * Returns correct entry media
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

// Get post format
$format = get_post_format();

// Display slider
if ( wpex_get_gallery_ids() ) :
	get_template_part( 'partials/entry', 'slider' );

// Display video
elseif ( get_post_meta( get_the_ID(), 'wpex_post_video', true ) ) :
	get_template_part( 'partials/entry', 'video' );

// Display post thumbnail
elseif ( has_post_thumbnail() ) : ?>
	<?php get_template_part( 'partials/entry', 'thumbnail' ); ?>
<?php endif ?>