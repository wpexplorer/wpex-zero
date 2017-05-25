<?php
/**
 * Used to display post author info
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

// Not needed here
if ( is_attachment() ) {
	return;
}

// Return if disabled
if ( ! get_theme_mod( 'post_author_bio', true ) ) {
	return;
}

// Vars
$author				= get_the_author();
$author_description	= get_the_author_meta( 'description' );
$author_url			= esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
$author_avatar		= get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'wpex_author_bio_avatar_size', 75 ) );

// Only display if author has a description
if ( ! $author_description ) {
	return;
} ?>

<div class="author-info clr">
	<h4 class="heading"><span><?php printf( __( 'Written by %s', 'wpex-zero' ), $author ); ?></span></h4>
	<div class="author-info-inner clr">
		<?php if ( $author_avatar ) { ?>
			<div class="author-avatar clr">
				<a href="<?php echo esc_url( $author_url ); ?>" rel="author">
					<?php echo $author_avatar; ?>
				</a>
			</div><!-- .author-avatar -->
		<?php } ?>
		<div class="author-description">
			<p><?php echo $author_description; ?></p>
			<p><a href="<?php echo esc_url( $author_url ); ?>" title="<?php _e( 'View all author posts', 'wpex-zero' ); ?>"><?php _e( 'View all author posts', 'wpex-zero' ); ?> &rarr;</a></p>
		</div><!-- .author-description -->
	</div><!-- .author-info-inner -->
</div><!-- .author-info -->