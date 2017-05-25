<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package		Zero
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		1.0.0
 */
?>

<aside id="secondary" class="sidebar-container clr" role="complementary">
	<div class="sidebar-inner clr">
		<?php
		// This theme has the header within the sidebar
		get_template_part( 'partials/header', 'layout' ); ?>
		<div class="hide-on-mobile clr">
			<?php get_template_part( 'partials/header', 'nav' ); ?>
			<?php
			// Display widgetized sidebar
			if ( is_active_sidebar( 'sidebar' ) ) : ?>
				<div class="widget-area">
					<?php dynamic_sidebar( 'sidebar' ); ?>
				</div><!-- .widget-area -->
			<?php endif; ?>
		</div><!-- .hide-on-mobile -->
	</div><!-- .sidebar-inner -->
</aside><!-- #secondary .sidebar-container -->