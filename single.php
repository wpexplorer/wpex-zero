<?php
/**
 * The Template for displaying all single posts.
 *
 * @package		Zero
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		1.0.0
 */

get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<div id="primary" class="content-area container clr">
			<main id="main" class="site-main clr" role="main">
				<div class="site-main-inner clr">
					<?php get_template_part( 'partials/post', 'layout' ); ?>
					<?php if ( apply_filters( 'wpex_theme_copy', true ) ) : ?>
						<div id="theme-copy" class="clearfix">
							<a href="http://www.wpexplorer.com/zero-responsive-blog-wordpress-theme/" title="Zero WordPress Theme" target="_blank">Zero WordPress Theme</a> by WPExplorer Powered by <a href="https://wordpress.org/" title="WordPress" target="_blank">WordPress</a>
						</div><!-- #theme-copy -->
					<?php endif; ?>
				</div><!-- .site-main-inner -->
			</main><!-- #main .site-main -->
			<?php get_sidebar(); ?>
		</div><!-- #primary -->
	<?php endwhile; ?>

<?php get_footer(); ?>