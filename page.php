<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
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
			<div class="site-main-inner clr">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'partials/page', 'layout' ); ?>
				<?php endwhile; ?>
			</div><!-- .site-main-inner -->
		</div><!-- #content .site-main -->
	</div><!-- #primary -->

<?php get_footer(); ?>