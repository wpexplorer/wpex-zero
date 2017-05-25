<?php
/**
 * Outputs the header navigation
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

// Location ID
$location = 'primary';

// Check to make sure menu isn't empty
if ( has_nav_menu( $location ) ) : ?>

    <div class="primary-menu-wrap clr">
        <a href="#sidr-main" class="mobile-navigation-toggle">
            <span class="fa fa-bars"></span><?php echo __( 'Menu', 'wpex-zero' ); ?>
        </a>
        <nav id="site-navigation" class="primary-menu clr" role="navigation">
            <?php wp_nav_menu( array(
                'theme_location'    => $location,
                'fallback_cb'       => false,
            ) ); ?>
        </nav><!-- .primary-menu -->
    </div><!-- .primary-menu-wrap -->

<?php endif; ?>