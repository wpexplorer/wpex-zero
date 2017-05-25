<?php
/**
 * Template Name: Archives
 *
 * @package     Zero
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @since       1.0.0
 */

get_header(); ?>

    <div id="primary" class="content-area container clr">
        <div id="content" class="site-main clr" role="main">
            <div class="site-main-inner clr">
                <div class="entry page-content page-article clr">

                    <?php get_template_part( 'partials/page', 'thumbnail' ); ?>
                    <?php get_template_part( 'partials/page', 'header' ); ?>

                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php the_content(); ?>
                    <?php endwhile; ?>

                    <div class="archives-template-box">
                        <h2><?php _e( 'Latest Posts', 'wpex-zero' ); ?></h2>
                        <ul>
                            <?php
                            query_posts( array(
                                'post_type'         => 'post',
                                'posts_per_page'    => '10',
                                'no_found_rows'     => true,
                            ) );
                            $count=0;
                            while ( have_posts() ) : the_post(); $count++; ?>
                                <li>
                                    <a href="<?php the_permalink() ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </li>
                            <?php endwhile; wp_reset_query(); ?>
                        </ul>
                    </div><!-- .archives-template-box -->

                    <div class="archives-template-box">
                        <h2><?php _e( 'Archives by Month', 'wpex-zero' ); ?></h2>
                        <ul><?php wp_get_archives('type=monthly'); ?></ul>
                    </div><!-- .archives-template-box -->

                    <div class="archives-template-box">
                        <h2><?php _e( 'Archives by Category', 'wpex-zero' ); ?></h2>
                        <ul><?php wp_list_categories( 'title_li=&hierarchical=0' ); ?></ul>
                    </div><!-- .archives-template-box -->

                </div><!-- .page-content -->
            </div><!-- .site-main-inner -->
        </div><!-- #content .site-main -->
    </div><!-- #primary -->

<?php get_footer(); ?>