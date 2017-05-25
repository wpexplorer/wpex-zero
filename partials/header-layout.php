<?php
/**
 * The main header layout
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

<div class="site-header-wrap clr">
	<header id="masthead" class="site-header container clr" role="banner">
		<?php get_template_part( 'partials/header', 'logo' ); ?>
		<?php get_template_part( 'partials/header', 'description' ); ?>
		<?php get_template_part( 'partials/mobile', 'toggle' ); ?>
	</header><!-- .site-header -->
</div><!-- .site-header-wrap -->