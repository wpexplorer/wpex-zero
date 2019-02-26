<?php
/**
 * Defines the array for the custom fields and metaboxes class
 *
 * @package     Zero
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @since       1.0.0
 */

// Only needed for the admin side
if ( ! is_admin() ) {
    return;
}

// Define meta array
if ( ! function_exists( 'wpex_metaboxes' ) ) {
    function wpex_metaboxes( array $meta_boxes ) {

        // Meta prefix
        $prefix = 'wpex_';

        // Posts
        $meta_boxes[] = array(
            'id'            => 'wpex-post-meta',
            'title'         => __( 'Post Settings', 'wpex-zero' ),
            'pages'         => array( 'post' ),
            'context'       => 'normal',
            'priority'      => 'high',
            'show_names'    => true,
            'fields'        => array(
                array(
                    'name'  => __( 'Video', 'wpex-zero' ),
                    'desc'  => __( 'Enter your embed code or enter in a URL that is compatible with WordPress\'s built-in oEmbed function or self-hosted video function.', 'wpex-zero' ),
                    'id'    => $prefix . 'post_video',
                    'type'  => 'textarea_code',
                    'std'   => '',
                ),
            ),
        );

        return $meta_boxes;
    }
}
add_filter( 'cmb_meta_boxes', 'wpex_metaboxes' );