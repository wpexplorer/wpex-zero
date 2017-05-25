<?php
/**
 * Edit post link
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

// Display edit post link
edit_post_link(
	__( 'Edit Post', 'wpex-zero' ),
	'<div class="post-edit clr">', '</div>'
); ?>