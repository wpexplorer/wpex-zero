<?php
/**
 * Displays the post tags
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

// Return if password protected
if ( post_password_required() ) {
	return;
} 

// Return if disabled
if ( ! get_theme_mod( 'post_tags', true ) ) {
	return;
}

the_tags( '<div class="post-tags clr">' . __( 'Tagged as', 'wpex-zero' ) .' ', ', ', '</div><!-- .post-tags -->' ); ?>