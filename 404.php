<?php
/**
 * The template for the 404 not found page
 *
 * @package		Zero
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		1.0.0
 */

get_header(); ?>

	<div id="primary" class="content-area container clr">
		<div id="content" class="site-main clr" role="main">
			<header class="archive-header">
				<h1 class="archive-header-title"><?php _e( '404 Error: Not Found', 'wpex-zero' ); ?></h1>
			</header>
			<div class="site-main-inner clr">
				<?php get_template_part( 'partials/entry', 'none' ); ?>
			</div><!-- .site-main-inner -->
		</div><!-- #content .site-main -->
	</div><!-- #primary -->

<?php get_footer(); ?>