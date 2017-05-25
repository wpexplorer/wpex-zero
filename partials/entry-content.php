<?php
/**
 * The post entry title
 *
 * @package     Zero
 * @subpackage  Partials
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Vars
$display    = get_theme_mod( 'wpex_entry_content_excerpt', 'excerpt' );
$length     = get_theme_mod( 'wpex_entry_excerpt_length', 45 ); ?>

<div class="loop-entry-excerpt entry clr">
    <?php if ( 'content' == $display ) : ?>
        <?php the_content(); ?>
    <?php else : ?>
        <?php wpex_excerpt( $length, false ); ?>
    <?php endif; ?>
</div><!--.loop-entry-excerpt -->