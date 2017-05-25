<?php
/**
 * Useful conditionals for this theme
 *
 * @package		Zero
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		1.0.0
*/

/**
 * Check if comments are enabled
 *
 * @since	1.0.0
 */
if ( ! function_exists( 'wpex_are_comments_enabled' ) ) {
	function wpex_are_comments_enabled() {
		$post_type = get_post_type();
		if ( 'portfolio' == $post_type && ! get_theme_mod( 'portfolio_comments', false ) ) {
			return false;
		} elseif ( 'staff' == $post_type && ! get_theme_mod( 'staff_comments', false ) ) {
			return false;
		} elseif ( 'page' == $post_type && ! get_theme_mod( 'page_comments', false ) ) {
			return false;
		} else {
			return true;
		}
	}
}