<?php
/**
 * The template for displaying Search Results pages.
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
				<?php get_template_part( 'partials/search', 'header' ); ?>
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
			</div><!-- .site-main-inner -->
		</main><!-- #main .site-main -->
		<?php get_sidebar(); ?>
	</div><!-- #primary -->

<?php get_footer(); ?>