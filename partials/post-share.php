<?php
/**
 * Outputs social sharing links for single posts
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

// Return if disabled
if ( ! get_theme_mod( 'post_share', true ) ) {
    return;
}

// Return if password protected
if ( post_password_required() ) {
    return;
}

// Post data
$post_id = get_the_ID();

// Sharing vars
if ( has_post_thumbnail() ) {
    $img = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
    $img = esc_url( $img );
} else {
    $img = false;
}

// Source URL
$source = home_url();

// Heading text
if ( is_attachment() ) {
    $heading = __( 'Share This Photo', 'wpex-zero' );
} else {
    $heading = __( 'Share This Post', 'wpex-zero' );
}
apply_filters( 'wpex_social_share_heading', $heading );

// Post Data
$permalink          = esc_url( get_permalink( $post_id ) );
$url                = urlencode( $permalink );
$title              = urlencode( esc_attr( the_title_attribute( 'echo=0' ) ) );
$summary            = urlencode( get_the_excerpt() );
$img                = urlencode( wp_get_attachment_url( get_post_thumbnail_id( $post_id ) ) );
$source             = urlencode( home_url() ); ?>

<div class="post-share clr">
    <?php if ( $heading ) : ?>
        <h4 class="heading"><?php echo esc_html( $heading ); ?></h4>
    <?php endif; ?>
    <ul class="post-share-list clr">
        <li class="share-twitter">
            <a href="http://twitter.com/share?text=<?php echo $title; ?>&amp;url=<?php echo $url; ?>" title="<?php _e( 'Share on Twitter', 'wpex-zero' ); ?>" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                <span class="fa fa-twitter"></span>
            </a>
        </li>
        <li class="share-facebook">
            <a href="http://www.facebook.com/share.php?u=<?php echo $url; ?>" title="<?php _e( 'Share on Facebook', 'wpex-zero' ); ?>" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                <span class="fa fa-facebook"></span>
            </a>
        </li>
        <li class="share-googleplus">
            <a href="https://plus.google.com/share?url=<?php echo $url; ?>" title="<?php _e( 'Share on Google+', 'wpex-zero' ); ?>" rel="external" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                <span class="fa fa-google-plus"></span>
            </a>
        </li>
        <li class="share-pinterest">
            <a href="http://pinterest.com/pin/create/button/?url=<?php echo $url; ?>&amp;media=<?php echo $img; ?>&amp;description=<?php echo $summary; ?>" title="<?php _e( 'Share on Pinterest', 'wpex-zero' ); ?>" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                <span class="fa fa-pinterest"></span>
            </a>
        </li>
        <li class="share-linkedin">
            <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>&amp;summary=<?php echo $summary; ?>&amp;source=<?php echo $source; ?>" title="<?php _e( 'Share on LinkedIn', 'wpex-zero' ); ?>" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                <span class="fa fa-linkedin"></span>
            </a>
        </li>
    </ul>
</div><!-- .post-share -->