<?php
/**
 * Outputs the page title
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

// Display the page header if it's not the front-page
if ( ! is_front_page() ) : ?>
	<header class="page-header clr">
	    <h1 class="page-header-title"><?php the_title(); ?></h1>
	</header><!-- #page-header -->
<?php endif; ?>