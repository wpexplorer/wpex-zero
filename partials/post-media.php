<?php
/**
 * Outputs the post media
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

<?php
// Display slider
if ( wpex_get_gallery_ids() ) :
	get_template_part( 'partials/post', 'slider' );

// Display video
elseif ( get_post_meta( get_the_ID(), 'wpex_post_video', true ) ) :
	get_template_part( 'partials/post', 'video' );

// Display post thumbnail
elseif ( has_post_thumbnail() ) : ?>
	<?php get_template_part( 'partials/post', 'thumbnail' ); ?>
<?php endif ?>
