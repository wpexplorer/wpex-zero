<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package		Zero
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		1.0.0
 */

get_header(); ?>

	<div id="primary" class="content-area container clr">
		<main id="main" class="site-main clr" role="main">
			<div class="site-main-inner clr">
				<?php get_template_part( 'partials/archive', 'header' ); ?>
				<?php if ( have_posts() ) : ?>
					<div class="entries clr">   
						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'partials/entry', 'layout' ); ?>
						<?php endwhile; ?>
					</div><!-- .entries -->
					<?php wpex_pagination(); ?>
				<?php else : ?>
					<?php get_template_part( 'partials/entry', 'none' ); ?>
				<?php endif; ?>
				<?php if ( apply_filters( 'wpex_theme_copy', true ) ) : ?>
					<div id="theme-copy" class="clearfix">
						<a href="http://www.wpexplorer.com/zero-responsive-blog-wordpress-theme/" title="Zero WordPress Theme" target="_blank">Zero WordPress Theme</a> by WPExplorer Powered by <a href="https://wordpress.org/" title="WordPress" target="_blank">WordPress</a>
					</div><!-- #theme-copy -->
				<?php endif; ?>
			</div><!-- .site-main-inner -->
		</main><!-- #main .site-main -->
	</div><!-- #primary -->

<?php get_footer(); ?>