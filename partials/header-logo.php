<?php
/**
 * Outputs the header logo
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

// Get vars for the logo
$custom_logo	= get_theme_mod( 'logo_img', get_template_directory_uri() .'/images/logo.png' );
$blog_name		= get_bloginfo( 'name' );
$home_url		= home_url();

// Sanitize data
$custom_logo	= esc_url( $custom_logo ); 
$blog_name		= esc_attr( $blog_name ); ?>

<div class="site-logo clr">
	<?php if ( $custom_logo ) { ?>
		<a href="<?php echo $home_url; ?>" title="<?php echo $blog_name; ?>" rel="home">
			<img src="<?php echo $custom_logo; ?>" alt="<?php echo $blog_name; ?>" />
		</a>
	<?php } else { ?>
		<div class="site-text-logo clr">
			<a href="<?php echo $home_url; ?>" title="<?php echo $blog_name; ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
		</div>
	<?php } ?>
</div><!-- .site-logo -->