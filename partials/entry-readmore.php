<?php
/**
 * Outputs a read more link for entries
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

// If disabled return
if ( ! get_theme_mod( 'wpex_entry_readmore', true ) ) {
	return;
}

// Readmore text
$text = get_theme_mod( 'readmore_text', __( 'Read More', 'wpex-zero' ) ); ?>

<div class="entry-readmore">
	<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( $text ); ?>" class="readmore"><?php echo esc_html( $text ); ?></a>
</div><!-- .entry-readmore -->