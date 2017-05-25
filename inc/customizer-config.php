<?php
/**
 * Defines all settings for the customizer class
 *
 * @package     Zero
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @since       1.0.0
 */

if ( ! function_exists( 'wpex_customizer_config' ) ) {

    function wpex_customizer_config( $panels ) {

        /*-----------------------------------------------------------------------------------*/
        /*  - Useful vars
        /*-----------------------------------------------------------------------------------*/
        
        // Font Weights
        $font_weights = array(
            ''      => __( 'Default', 'wpex-zero' ),
            '100'   => '100',
            '200'   => '200',
            '300'   => '300',
            '400'   => '400',
            '500'   => '500',
            '600'   => '600',
            '700'   => '700',
            '800'   => '800',
            '900'   => '900',
        );

        /*-----------------------------------------------------------------------------------*/
        /*  - General Panel
        /*-----------------------------------------------------------------------------------*/
        $panels['general'] = array(
            'title'     => __( 'General Theme Settings', 'wpex-zero' ),
            'sections'  => array(

                // Logo Section
                array(
                    'id'        => 'wpex_logo',
                    'title'     => __( 'Logo', 'wpex-zero' ),
                    'settings'  => array(
                        array(
                            'id'        => 'logo_img',
                            'default'   => get_template_directory_uri() .'/images/logo.png',
                            'control'   => array(
                                'label' => __( 'Custom Logo', 'wpex-zero' ),
                                'type'  => 'upload',
                            ),
                        ),
                        array(
                            'id'        => 'logo_img_retina',
                            'control'   => array(
                                'label' => __( 'Custom Retina Logo', 'wpex-zero' ),
                                'type'  => 'upload',
                            ),
                        ),
                        array(
                            'id'        => 'logo_img_height',
                            'control'   => array(
                                'label' => __( 'Standard Logo Height', 'wpex-zero' ),
                                'desc'  => __( 'Enter the standard height for your logo. Used to set your retina logo to the correct dimensions', 'wpex-zero' ),
                            ),
                        ),
                    ),
                ),

                // Posts
                array(
                    'id'        => 'wpex_single_posts',
                    'title'     => __( 'Entries & Posts', 'wpex-zero' ),
                    'settings'  => array(
                        array(
                            'id'        => 'entry_thumbnail',
                            'default'   => true,
                            'control'   => array(
                                'label' => __( 'Entry Thumbnail', 'wpex-zero' ),
                                'type'  => 'checkbox',
                            ),
                        ),
                        array(
                            'id'        => 'entry_meta',
                            'default'   => true,
                            'control'   => array(
                                'label' => __( 'Entry Meta', 'wpex-zero' ),
                                'type'  => 'checkbox',
                            ),
                        ),
                        array(
                            'id'        => 'post_thumbnail',
                            'default'   => true,
                            'control'   => array(
                                'label' => __( 'Post Thumbnail', 'wpex-zero' ),
                                'type'  => 'checkbox',
                            ),
                        ),
                        array(
                            'id'        => 'post_meta',
                            'default'   => true,
                            'control'   => array(
                                'label' => __( 'Post Meta', 'wpex-zero' ),
                                'type'  => 'checkbox',
                            ),
                        ),
                        array(
                            'id'        => 'post_tags',
                            'default'   => true,
                            'control'   => array(
                                'label' => __( 'Post Tags', 'wpex-zero' ),
                                'type'  => 'checkbox',
                            ),
                        ),
                        array(
                            'id'        => 'post_share',
                            'default'   => true,
                            'control'   => array(
                                'label' => __( 'Post Social Share', 'wpex-zero' ),
                                'type'  => 'checkbox',
                            ),
                        ),
                        array(
                            'id'        => 'post_author_bio',
                            'default'   => true,
                            'control'   => array(
                                'label' => __( 'Post Author Box', 'wpex-zero' ),
                                'type'  => 'checkbox',
                            ),
                        ),
                    ),
                ),

            ),
        );


        /*-----------------------------------------------------------------------------------*/
        /*  - Typography
        /*-----------------------------------------------------------------------------------*/
        $panels['typography'] = array(
            'title'     => __( 'Typography', 'wpex-zero' ),
            'sections'  => array(

                // Logo Typography
                array(
                    'id'        => 'wpex_logo_typography',
                    'title'     => __( 'Logo', 'wpex-zero' ),
                    'settings'  => array(
                        array(
                            'id'        => 'logo_font_family',
                            'default'   => 'Merriweather Sans',
                            'control'   => array(
                                'label' => __( 'Font Family', 'wpex-zero' ),
                                'type'  => 'google_font',
                            ),
                            'inline_css'    => array(
                                'target'    => '.site-logo',
                                'alter'     => 'font-family',
                            ),
                        ),
                        array(
                            'id'        => 'logo_font_weight',
                            'control'   => array(
                                'label'     => __( 'Font Weight', 'wpex-zero' ),
                                'type'      => 'select',
                                'choices'   => $font_weights,
                            ),
                            'inline_css'    => array(
                                'target'    => '.site-logo .site-text-logo',
                                'alter'     => 'font-weight',
                            ),
                        ),
                        array(
                            'id'        => 'logo_size',
                            'control'   => array(
                                'label'     => __( 'Font Size', 'wpex-zero' ),
                                'desc'      => __( 'Leave at 0 to use theme default.', 'wpex-zero' ),
                                'type'      => 'ui-slider',
                                'choices'   => array(
                                    'min'   => '0',
                                    'max'   => '40',
                                    'step'  => '1'
                                ),
                            ),
                            'inline_css'    => array(
                                'target'    => '.site-logo .site-text-logo',
                                'alter'     => 'font-size',
                                'sanitize'  => 'px',
                            ),
                        ),
                        array(
                            'id'        => 'logo_color',
                            'control'   => array(
                                'label' => __( 'Text Color', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.site-logo a',
                                'alter'     => 'color',
                                'sanitize'  => 'hex',
                                'important' => true,
                            ),
                        ),
                    ),
                ),

                // Body Typography
                array(
                    'id'        => 'wpex_body_typography',
                    'title'     => __( 'Body', 'wpex-zero' ),
                    'settings'  => array(
                        array(
                            'id'        => 'body_font_family',
                            'default'   => 'Merriweather Sans',
                            'control'   => array(
                                'label' => __( 'Font Family', 'wpex-zero' ),
                                'type'  => 'google_font',
                            ),
                            'inline_css'    => array(
                                'target'    => 'body, a#cancel-comment-reply-link',
                                'alter'     => 'font-family',
                            ),
                        ),
                        array(
                            'id'        => 'body_font_weight',
                            'control'   => array(
                                'label'     => __( 'Font Weight', 'wpex-zero' ),
                                'type'      => 'select',
                                'choices'   => $font_weights,
                            ),
                            'inline_css'    => array(
                                'target'    => 'body',
                                'alter'     => 'font-weight',
                            ),
                        ),
                        array(
                            'id'        => 'body_font_size',
                            'control'   => array(
                                'label'     => __( 'Font Size', 'wpex-zero' ),
                                'desc'      => __( 'Leave at 0 to use theme default.', 'wpex-zero' ),
                                'type'      => 'ui-slider',
                                'choices'   => array(
                                    'min'   => '0',
                                    'max'   => '40',
                                    'step'  => '1'
                                ),
                            ),
                            'inline_css'    => array(
                                'target'    => 'body',
                                'alter'     => 'font-size',
                                'sanitize'  => 'px',
                            ),
                        ),
                    ),
                ),

                // Headings Typography
                array(
                    'id'        => 'wpex_headings_typography',
                    'title'     => __( 'Heading Tags', 'wpex-zero' ),
                    'desc'      => 'h1, h2, h3, h4, h5, h6',
                    'settings'  => array(
                        array(
                            'id'        => 'headings_font_family',
                            'default'   => 'Playfair Display',
                            'control'   => array(
                                'label' => __( 'Font Family', 'wpex-zero' ),
                                'type'  => 'google_font',
                            ),
                            'inline_css'    => array(
                                'target'    => 'h1,h2,h3,h4,h5,h6',
                                'alter'     => 'font-family',
                            ),
                        ),
                        array(
                            'id'        => 'headings_font_weight',
                            'control'   => array(
                                'label'     => __( 'Font Weight', 'wpex-zero' ),
                                'type'      => 'select',
                                'choices'   => $font_weights,
                            ),
                            'inline_css'    => array(
                                'target'    => 'h1,h2,h3,h4,h5,h6',
                                'alter'     => 'font-weight',
                            ),
                        ),
                    ),
                ),

                // Widget Titles Typography
                array(
                    'id'        => 'wpex_widget_heading_typography',
                    'title'     => __( 'Widget Headings', 'wpex-zero' ),
                    'settings'  => array(
                        array(
                            'id'        => 'widget_headings_font_family',
                            'default'   => 'Merriweather Sans',
                            'control'   => array(
                                'label' => __( 'Font Family', 'wpex-zero' ),
                                'type'  => 'google_font',
                            ),
                            'inline_css'    => array(
                                'target'    => '.widget-title',
                                'alter'     => 'font-family',
                            ),
                        ),
                        array(
                            'id'        => 'widget_headings_font_weight',
                            'control'   => array(
                                'label'     => __( 'Font Weight', 'wpex-zero' ),
                                'type'      => 'select',
                                'choices'   => $font_weights,
                            ),
                            'inline_css'    => array(
                                'target'    => '.widget-title',
                                'alter'     => 'font-weight',
                            ),
                        ),
                        array(
                            'id'        => 'widget_headings_size',
                            'control'   => array(
                                'label'     => __( 'Font Size', 'wpex-zero' ),
                                'desc'      => __( 'Leave at 0 to use theme default.', 'wpex-zero' ),
                                'type'      => 'ui-slider',
                                'choices'   => array(
                                    'min'   => '0',
                                    'max'   => '40',
                                    'step'  => '1'
                                ),
                            ),
                            'inline_css'    => array(
                                'target'    => '.widget-title',
                                'alter'     => 'font-size',
                                'sanitize'  => 'px',
                            ),
                        ),
                        array(
                            'id'        => 'wpex_widget_headings_color',
                            'control'   => array(
                                'label' => __( 'Text Color', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.widget-title',
                                'alter'     => 'color',
                                'sanitize'  => 'hex',
                            ),
                        ),
                    ),
                ),

                // Entry Title Typography
                array(
                    'id'        => 'wpex_entry_title_typography',
                    'title'     => __( 'Entry Title', 'wpex-zero' ),
                    'settings'  => array(
                        array(
                            'id'        => 'entry_title_font_family',
                            'control'   => array(
                                'label' => __( 'Font Family', 'wpex-zero' ),
                                'type'  => 'google_font',
                            ),
                            'inline_css'    => array(
                                'target'    => '.loop-entry-title',
                                'alter'     => 'font-family',
                            ),
                        ),
                        array(
                            'id'        => 'entry_title_font_weight',
                            'control'   => array(
                                'label'     => __( 'Font Weight', 'wpex-zero' ),
                                'type'      => 'select',
                                'choices'   => $font_weights,
                            ),
                            'inline_css'    => array(
                                'target'    => '.loop-entry-title',
                                'alter'     => 'font-weight',
                            ),
                        ),
                        array(
                            'id'        => 'entry_title_size',
                            'control'   => array(
                                'label'     => __( 'Font Size', 'wpex-zero' ),
                                'desc'      => __( 'Leave at 0 to use theme default.', 'wpex-zero' ),
                                'type'      => 'ui-slider',
                                'choices'   => array(
                                    'min'   => '0',
                                    'max'   => '40',
                                    'step'  => '1'
                                ),
                            ),
                            'inline_css'    => array(
                                'target'    => '.loop-entry-title',
                                'alter'     => 'font-size',
                                'sanitize'  => 'px',
                            ),
                        ),
                        array(
                            'id'        => 'entry_title_color',
                            'control'   => array(
                                'label' => __( 'Text Color', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.loop-entry-title a',
                                'alter'     => 'color',
                                'sanitize'  => 'hex',
                            ),
                        ),
                    ),
                ),

                // Post Title Typography
                array(
                    'id'        => 'wpex_post_title_typography',
                    'title'     => __( 'Post Title', 'wpex-zero' ),
                    'settings'  => array(
                        array(
                            'id'        => 'post_title_font_family',
                            'control'   => array(
                                'label' => __( 'Font Family', 'wpex-zero' ),
                                'type'  => 'google_font',
                            ),
                            'inline_css'    => array(
                                'target'    => '.post-title',
                                'alter'     => 'font-family',
                            ),
                        ),
                        array(
                            'id'        => 'post_title_font_weight',
                            'control'   => array(
                                'label'     => __( 'Font Weight', 'wpex-zero' ),
                                'type'      => 'select',
                                'choices'   => $font_weights,
                            ),
                            'inline_css'    => array(
                                'target'    => '.post-title',
                                'alter'     => 'font-weight',
                            ),
                        ),
                        array(
                            'id'        => 'post_title_size',
                            'control'   => array(
                                'label'     => __( 'Font Size', 'wpex-zero' ),
                                'desc'      => __( 'Leave at 0 to use theme default.', 'wpex-zero' ),
                                'type'      => 'ui-slider',
                                'choices'   => array(
                                    'min'   => '0',
                                    'max'   => '40',
                                    'step'  => '1'
                                ),
                            ),
                            'inline_css'    => array(
                                'target'    => '.post-title',
                                'alter'     => 'font-size',
                                'sanitize'  => 'px',
                            ),
                        ),
                        array(
                            'id'        => 'post_title_color',
                            'control'   => array(
                                'label' => __( 'Text Color', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.post-title',
                                'alter'     => 'color',
                                'sanitize'  => 'hex',
                            ),
                        ),
                    ),
                ),

                // Post Typography
                array(
                    'id'        => 'wpex_post_typography',
                    'title'     => __( 'Main Content', 'wpex-zero' ),
                    'settings'  => array(
                        array(
                            'id'        => 'post_font_family',
                            'control'   => array(
                                'label' => __( 'Font Family', 'wpex-zero' ),
                                'type'  => 'google_font',
                            ),
                            'inline_css'    => array(
                                'target'    => '.entry',
                                'alter'     => 'font-family',
                            ),
                        ),
                        array(
                            'id'        => 'post_font_weight',
                            'control'   => array(
                                'label'     => __( 'Font Weight', 'wpex-zero' ),
                                'type'      => 'select',
                                'choices'   => $font_weights,
                            ),
                            'inline_css'    => array(
                                'target'    => '.entry',
                                'alter'     => 'font-weight',
                            ),
                        ),
                        array(
                            'id'        => 'post_size',
                            'control'   => array(
                                'label'     => __( 'Font Size', 'wpex-zero' ),
                                'desc'      => __( 'Leave at 0 to use theme default.', 'wpex-zero' ),
                                'type'      => 'ui-slider',
                                'choices'   => array(
                                    'min'   => '0',
                                    'max'   => '40',
                                    'step'  => '1'
                                ),
                            ),
                            'inline_css'    => array(
                                'target'    => '.entry',
                                'alter'     => 'font-size',
                                'sanitize'  => 'px',
                            ),
                        ),
                        array(
                            'id'        => 'post_color',
                            'control'   => array(
                                'label' => __( 'Text Color', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.entry',
                                'alter'     => 'color',
                                'sanitize'  => 'hex',
                            ),
                        ),
                    ),
                ),

            ),
        );

        /*-----------------------------------------------------------------------------------*/
        /*  - Styling Panel
        /*-----------------------------------------------------------------------------------*/
        $panels['styling'] = array(
            'title'     => __( 'Styling', 'wpex-zero' ),
            'sections'  => array(

                // Main
                array(
                    'id'        => 'wpex_styling_main',
                    'title'     => __( 'Main', 'wpex-zero' ),
                    'settings'  => array(
                        array(
                            'id'        => 'highlight_color',
                            'control'   => array(
                                'label' => __( 'Highlight Color', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.post-share-list li a:hover,button, input[type="button"], input[type="submit"], .entry-readmore .readmore:hover, .comment-footer a:hover, a#cancel-comment-reply-link:hover',
                                'alter'     => 'background-color',
                                'sanitize'  => 'hex',
                            ),
                        ),
                        array(
                            'id'        => 'link_color',
                            'control'   => array(
                                'label' => __( 'Links', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => 'a, h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover',
                                'sanitize'  => 'hex',
                                'alter'     => 'color',
                            ),
                        ),
                        array(
                            'id'        => 'link_color_hover',
                            'control'   => array(
                                'label' => __( 'Links: Hover', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => 'a:hover',
                                'sanitize'  => 'hex',
                                'alter'     => 'color',
                            ),
                        ),
                    ),
                ),

                // Sidebar
                array(
                    'id'        => 'wpex_styling_sidebar',
                    'title'     => __( 'Sidebar', 'wpex-zero' ),
                    'settings'  => array(
                        array(
                            'id'        => 'sidebar_bg',
                            'control'   => array(
                                'label' => __( 'Background', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.sidebar-container',
                                'alter'     => 'background-color',
                                'sanitize'  => 'hex',
                            ),
                        ),
                        array(
                            'id'        => 'sidebar_text_color',
                            'control'   => array(
                                'label' => __( 'Text', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.sidebar-container',
                                'alter'     => 'color',
                                'sanitize'  => 'hex',
                            ),
                        ),
                        array(
                            'id'        => 'sidebar_links_color',
                            'control'   => array(
                                'label' => __( 'Links', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.sidebar-container a',
                                'alter'     => 'color',
                                'sanitize'  => 'hex',
                            ),
                        ),
                        array(
                            'id'        => 'sidebar_links_hover_color',
                            'control'   => array(
                                'label' => __( 'Links: Hover', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.sidebar-container a:hover',
                                'alter'     => 'color',
                                'sanitize'  => 'hex',
                            ),
                        ),
                        array(
                            'id'        => 'sidebar_dividers',
                            'control'   => array(
                                'label' => __( 'Dividers', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.sidebar-container .sidebar-widget:before, .sidebar-container #site-navigation:before,',
                                'alter'     => 'background-color',
                                'sanitize'  => 'hex',
                            ),
                        ),
                        array(
                            'id'        => 'sidebar_borders',
                            'control'   => array(
                                'label' => __( 'Borders', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.sidebar-container .widget-recent-list li',
                                'alter'     => 'border-color',
                                'sanitize'  => 'hex',
                            ),
                        ),
                        array(
                            'id'        => 'sidebar_search_bg',
                            'control'   => array(
                                'label' => __( 'Search Background', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.sidebar-container .site-searchform input[type="search"]',
                                'alter'     => 'background-color',
                                'sanitize'  => 'hex',
                            ),
                        ),
                        array(
                            'id'        => 'sidebar_search_color',
                            'control'   => array(
                                'label' => __( 'Search Color', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.sidebar-container .site-searchform input[type="search"],.sidebar-container .site-searchform button',
                                'alter'     => 'color',
                                'sanitize'  => 'hex',
                            ),
                        ),
                        array(
                            'id'        => 'sidebar_tags_bg',
                            'control'   => array(
                                'label' => __( 'Tags Background', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.sidebar-container .widget_tag_cloud a, #wpex-widget-tags-tab a',
                                'alter'     => 'background-color',
                                'sanitize'  => 'hex',
                            ),
                        ),
                        array(
                            'id'        => 'sidebar_tags_color',
                            'control'   => array(
                                'label' => __( 'Tags Color', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.sidebar-container .widget_tag_cloud a, #wpex-widget-tags-tab a',
                                'alter'     => 'color',
                                'sanitize'  => 'hex',
                                'important' => true,
                            ),
                        ),
                        array(
                            'id'        => 'sidebar_tab_bg',
                            'control'   => array(
                                'label' => __( 'Widget Tab Background', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.wpex-tabs-widget-tabs li a',
                                'alter'     => 'background-color',
                                'sanitize'  => 'hex',
                                'important' => true,
                            ),
                        ),
                        array(
                            'id'        => 'sidebar_tab_bg_hover',
                            'control'   => array(
                                'label' => __( 'Widget Tab Background: Hover/Active', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.wpex-tabs-widget-tabs li a:hover, .wpex-tabs-widget-tabs li.active a',
                                'alter'     => 'background-color',
                                'sanitize'  => 'hex',
                                'important' => true,
                            ),
                        ),
                        array(
                            'id'        => 'sidebar_tab_color',
                            'control'   => array(
                                'label' => __( 'Widget Tab Color', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.wpex-tabs-widget-tabs li a',
                                'alter'     => 'color',
                                'sanitize'  => 'hex',
                                'important' => true,
                            ),
                        ),
                        array(
                            'id'        => 'sidebar_tab_color_hover',
                            'control'   => array(
                                'label' => __( 'Widget Tab Color: Hover/Active', 'wpex-zero' ),
                                'type'  => 'color',
                            ),
                            'inline_css'    => array(
                                'target'    => '.wpex-tabs-widget-tabs li a:hover, .wpex-tabs-widget-tabs li.active a',
                                'alter'     => 'color',
                                'sanitize'  => 'hex',
                                'important' => true,
                            ),
                        ),
                    ),
                ),

            ),
        );

        /*-----------------------------------------------------------------------------------*/
        /*  - Image Sizes
        /*-----------------------------------------------------------------------------------*/
        $panels['image_sizes'] = array(
            'title'     => __( 'Image Sizes', 'wpex-zero' ),
            'sections'  => array(

                // Entries
                array(
                    'id'        => 'wpex_entry_thumbnail_sizes',
                    'title'     => __( 'Entries', 'wpex-zero' ),
                    'desc'      => __( 'If you alter any image sizes you will have to regenerate your thumbnails.', 'wpex-zero' ),
                    'settings'  => array(
                        array(
                            'id'        => 'entry_thumbnail_width',
                            'default'   => '940',
                            'control'   => array(
                                'label' => __( 'Image Width', 'wpex-zero' ),
                                'type'  => 'text',
                            ),
                        ),
                        array(
                            'id'        => 'entry_thumbnail_height',
                            'default'   => '480',
                            'control'   => array(
                                'label' => __( 'Image Height', 'wpex-zero' ),
                                'type'  => 'text',
                            ),
                        ),
                        array(
                            'id'        => 'entry_thumbnail_crop',
                            'default'   => true,
                            'control'   => array(
                                'label' => __( 'Force Crop', 'wpex-zero' ),
                                'type'  => 'checkbox',
                            ),
                        ),
                    ),
                ),

                // Posts
                array(
                    'id'        => 'wpex_post_thumbnail_sizes',
                    'title'     => __( 'Posts', 'wpex-zero' ),
                    'desc'      => __( 'If you alter any image sizes you will have to regenerate your thumbnails.', 'wpex-zero' ),
                    'settings'  => array(
                        array(
                            'id'        => 'post_thumbnail_width',
                            'default'   => '940',
                            'control'   => array(
                                'label' => __( 'Image Width', 'wpex-zero' ),
                                'type'  => 'text',
                            ),
                        ),
                        array(
                            'id'        => 'post_thumbnail_height',
                            'default'   => '480',
                            'control'   => array(
                                'label' => __( 'Image Height', 'wpex-zero' ),
                                'type'  => 'text',
                            ),
                        ),
                        array(
                            'id'        => 'post_thumbnail_crop',
                            'default'   => true,
                            'control'   => array(
                                'label' => __( 'Force Crop', 'wpex-zero' ),
                                'type'  => 'checkbox',
                            ),
                        ),
                    ),
                ),

            ),
        );

        // Return panels array
        return $panels;

    }
}
add_filter( 'wpex_customizer_panels', 'wpex_customizer_config' );