<?php
/**
 * Post pagination
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

// Post pagination
wp_link_pages( array(
	'before'		=> '<div class="page-links clr">',
	'after'			=> '</div>',
	'link_before'	=> '<span>',
	'link_after'	=> '</span>',
) ); ?>