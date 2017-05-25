<?php
/**
 * Search archive header
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

<header class="archive-header">
	<h1 class="archive-title">
		<?php printf( __( 'Search Results for: %s', 'wpex-zero' ), get_search_query() ); ?>
	</h1><!-- .archive-title -->
</header><!-- .archive-header -->