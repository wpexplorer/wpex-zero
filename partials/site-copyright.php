<?php
/**
 * Site copyright info
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

// Get copyright data
$copy = get_theme_mod( 'copyright', '<a href="http://www.wordpress.org" title="WordPress" target="_blank">WordPress</a> Theme Designed &amp; Developed by <a href="http://www.wpexplorer.com/" target="_blank" title="WPExplorer">WPExplorer</a>' );

// Sanitize data
$copy = wpex_sanitize_data( $copy, 'html' ) ?>

<?php if ( $copy ) : ?>
	<div class="site-copyright-wrap clr">
		<div class="site-copyright clr">
			<?php echo do_shortcode( $copy ); ?>	
		</div><!-- .site-copyright -->
	</div><!-- .site-copyright-wrap -->
<?php endif; ?>