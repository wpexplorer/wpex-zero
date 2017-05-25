<?php
/**
 * The template for displaying a "No posts found" message.
 *
 * @package		Zero
 * @subpackage	Partials
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		1.0.0
 */ ?>

<div class="entry-none">
	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) { ?>
		<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'wpex-zero' ), admin_url( 'post-new.php' ) ); ?></p>
	<?php } elseif ( is_search() ) { ?>
		<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'wpex-zero' ); ?></p>
	<?php } elseif ( is_category() ) { ?>
		<p><?php _e( 'There aren\'t any posts currently published in this category.', 'wpex-zero' ); ?></p>
	<?php } elseif ( is_tax() ) { ?>
		<p><?php _e( 'There aren\'t any posts currently published under this taxonomy.', 'wpex-zero' ); ?></p>
	<?php } elseif ( is_tag() ) { ?>
		<p><?php _e( 'There aren\'t any posts currently published under this tag.', 'wpex-zero' ); ?></p>
	<?php } elseif ( is_404() ) { ?>
		<p><?php _e( 'Unfortunately, the page you are looking for does not exist.', 'wpex-zero' ); ?></p>
	<?php } else { ?>
		<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'wpex-zero' ); ?></p>
	<?php } ?>
</div><!-- .entry-none -->