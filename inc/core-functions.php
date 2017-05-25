<?php
/**
 * Core functions used for the theme
 *
 * @package     Zero
 * @author      Alexander Clarke
 * @copyright   Copyright (c) 2014, Symple Workz LLC
 * @link        http://www.wpexplorer.com
 * @since       1.0.0
 */

/**
 * Sanitizes data
 *
 * @since   1.0.0
 * @return  string
 */
if ( ! function_exists( 'wpex_sanitize_data' ) ) {
    function wpex_sanitize_data( $data, $type ) {

        // URL
        if ( 'url' == $type ) {
            $data = esc_url( $data );
        }

        // HTML
        elseif ( 'html' == $type ) {
            $data = wp_kses( $data, array(
                    'a'         => array(
                        'href'  => array(),
                        'title' => array()
                    ),
                    'br'        => array(),
                    'em'        => array(),
                    'strong'    => array(),
            ) );
        }

        // Videos
        elseif ( 'video' == $type ) {
            $data = wp_kses( $data, array(
                'iframe' => array(
                    'src'               => array(),
                    'type'              => array(),
                    'allowfullscreen'   => array(),
                    'allowscriptaccess' => array(),
                    'height'            => array(),
                    'width'             => array()
                ),
                'embed' => array(
                    'src'               => array(),
                    'type'              => array(),
                    'allowfullscreen'   => array(),
                    'allowscriptaccess' => array(),
                    'height'            => array(),
                    'width'             => array()
                ),
            ) );
        }
        return $data;
    }
}

/**
 * Checks a custom field value and returns the type (embed, oembed, etc )
 *
 * @since   1.0.0
 * @return  string
 */
if ( ! function_exists( 'wpex_check_meta_type' ) ) {
    function wpex_check_meta_type( $value ) {
        if ( strpos( $value, 'embed' ) ) {
            return 'embed';
        } elseif ( strpos( $value, 'iframe' ) ) {
            return 'iframe';
        } else {
            return 'url';
        }
    }
}

/**
 * Custom menu walker
 * 
 * @link    http://codex.wordpress.org/Class_Reference/Walker_Nav_Menu
 * @since   1.0.0
 */
if ( ! class_exists( 'WPEX_Dropdown_Walker_Nav_Menu' ) ) {
    class WPEX_Dropdown_Walker_Nav_Menu extends Walker_Nav_Menu {
        function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
            $id_field = $this->db_fields['id'];
            if ( ! empty( $children_elements[$element->$id_field] ) && ( $depth == 0 ) ) {
                $element->classes[] = 'dropdown';
                $element->title .= ' <i class="fa fa-angle-down dropdown-arrow"></i>';
            }
            if ( ! empty( $children_elements[$element->$id_field] ) && ( $depth > 0 ) ) {
                $element->classes[] = 'dropdown';
                $element->title .= ' <i class="fa fa-angle-right"></i>';
            }
            Walker_Nav_Menu::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
        }
    }
}

/**
 * Custom comments callback
 * 
 * @link    http://codex.wordpress.org/Function_Reference/wp_list_comments
 * @since   1.0.0
 */
if ( ! function_exists( 'wpex_comment' ) ) {
    function wpex_comment( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;
        switch ( $comment->comment_type ) :
            case 'pingback' :
            case 'trackback' :
                // Display trackbacks differently than normal comments. ?>
                <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
                <p><strong><?php _e( 'Pingback:', 'wpex-zero' ); ?></strong> <?php comment_author_link(); ?></p>
            <?php
            break;
            default :
                // Proceed with normal comments. ?>
                <li id="li-comment-<?php comment_ID(); ?>">
                    <div id="comment-<?php comment_ID(); ?>" <?php comment_class( 'clr' ); ?>>
                        <div class="comment-author vcard">
                            <?php echo get_avatar( $comment, 50 ); ?>
                        </div><!-- .comment-author -->
                        <div class="comment-details clr">
                            <header class="comment-meta">
                                <cite class="fn"><?php comment_author_link(); ?></cite>
                                <span class="comment-date">
                                <?php
                                    printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
                                        esc_url( get_comment_link( $comment->comment_ID ) ),
                                        get_comment_time( 'c' ),
                                        sprintf( _x( '%1$s', '1: date', 'wpex-zero' ), get_comment_date() )
                                    ); ?>
                                </span><!-- .comment-date -->
                            </header><!-- .comment-meta -->
                            <?php if ( '0' == $comment->comment_approved ) : ?>
                                <p class="comment-awaiting-moderation">
                                    <?php _e( 'Your comment is awaiting moderation.', 'wpex-zero' ); ?>
                                </p><!-- .comment-awaiting-moderation -->
                            <?php endif; ?>
                            <div class="comment-content entry clr">
                                <?php comment_text(); ?>
                            </div><!-- .comment-content -->
                            <footer class="comment-footer clr">
                                <?php
                                // Cancel comment link
                                comment_reply_link( array_merge( $args, array(
                                    'reply_text'    => __( 'Reply', 'wpex-zero' ) . '',
                                    'depth'         => $depth,
                                    'max_depth'     => $args['max_depth']
                                ) ) ); ?>
                                <?php
                                // Edit comment link
                                edit_comment_link( __( 'Edit', 'wpex-zero' ), '<div class="edit-comment">', '</div>' ); ?>
                            </footer>
                        </div><!-- .comment-details -->
                    </div><!-- #comment-## -->
            <?php
            break;
        endswitch;
    }
}

/**
 * Custom excerpts based on wp_trim_words
 * Created for child-theming purposes
 * 
 * @link    http://codex.wordpress.org/Function_Reference/wp_trim_words
 * @since   1.0.0
 */
if ( ! function_exists( 'wpex_excerpt' ) ) {
    function wpex_excerpt( $length = 30, $readmore = false ) {
        global $post;
        $id = $post->ID;
        if ( has_excerpt( $id ) ) {
            $output = $post->post_excerpt;
        } else {
            if ( strpos( $post->post_content, '<!--more-->' ) ) {
                $output = get_the_content( '' );
            } else {
                $output = wp_trim_words( strip_shortcodes( get_the_content( $id ) ), $length);
                if ( $readmore == true ) {
                    $readmore_link = false; // Remove readmore link for this theme
                    $output .= apply_filters( 'wpex_readmore_link', $readmore_link );
                }
            }
        }
        echo $output;
    }
}

/**
 * Pagination function
 *
 * @link    http://codex.wordpress.org/Function_Reference/paginate_links
 * @since   1.0.0
 */
if ( ! function_exists( 'wpex_pagination') ) {  
    function wpex_pagination() {
        global $wp_query, $wpex_query;
        if ( $wpex_query ) {
            $total = $wpex_query->max_num_pages;
        } else {
            $total = $wp_query->max_num_pages;
        }
        $big = 999999999; // need an unlikely integer
        if ( $total > 1 )  {
             if ( ! $current_page = get_query_var( 'paged') )
                 $current_page = 1;
             if ( get_option( 'permalink_structure') ) {
                 $format = 'page/%#%/';
             } else {
                 $format = '&paged=%#%';
             }
            echo paginate_links( array(
                'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format'    => $format,
                'current'   => max( 1, get_query_var( 'paged') ),
                'total'     => $total,
                'mid_size'  => 2,
                'type'      => 'list',
                'prev_text' => '<i class="fa fa-angle-left"></i>',
                'next_text' => '<i class="fa fa-angle-right"></i>',
             ));
        }
    }
}

/**
 * List categories for specific taxonomy
 * 
 * @link    http://codex.wordpress.org/Function_Reference/wp_get_post_terms
 * @since   1.0.0
 */
if ( ! function_exists( 'wpex_list_post_terms' ) ) {
    function wpex_list_post_terms( $taxonomy = 'category', $echo = true ) {
        $list_terms = array();
        $terms      = wp_get_post_terms( get_the_ID(), $taxonomy );
        foreach ( $terms as $term ) {
            $permalink      = get_term_link( $term->term_id, $taxonomy );
            $list_terms[]   = '<a href="'. $permalink .'" title="'. $term->name .'">'. $term->name .'</a>';
        }
        if ( ! $list_terms ) {
            return;
        }
        $list_terms = implode( ', ', $list_terms );
        if ( $echo ) {
            echo $list_terms;
        } else {
            return $list_terms;
        }
    }
}

/**
 * Array of Font Awesome Icons
 * Learn more: http://fortawesome.github.io/Font-Awesome/
 *
 * @since   1.0.0
 * @return  array
 */
if ( ! function_exists( 'wpex_get_awesome_icons' ) ) { 
    function wpex_get_awesome_icons() {
        $icons = array('none'=>'','adjust'=>'adjust','anchor'=>'anchor','archive'=>'archive','arrows'=>'arrows','arrows-h'=>'arrows-h','arrows-v'=>'arrows-v','asterisk'=>'asterisk','automobile'=>'automobile','ban'=>'ban','bank'=>'bank','bar-chart'=>'bar-chart','bar-chart-o'=>'bar-chart-o','barcode'=>'barcode','bars'=>'bars','beer'=>'beer','bell'=>'bell','bell-o'=>'bell-o','bolt'=>'bolt','bomb'=>'bomb','book'=>'book','bookmark'=>'bookmark','bookmark-o'=>'bookmark-o','briefcase'=>'briefcase','bug'=>'bug','building'=>'building','building-o'=>'building-o','bullhorn'=>'bullhorn','bullseye'=>'bullseye','cab'=>'cab','calendar'=>'calendar','calendar-o'=>'calendar-o','camera'=>'camera','camera-retro'=>'camera-retro','car'=>'car','caret-square-o-down'=>'caret-square-o-down','caret-square-o-left'=>'caret-square-o-left','caret-square-o-right'=>'caret-square-o-right','caret-square-o-up'=>'caret-square-o-up','certificate'=>'certificate','check'=>'check','check-circle'=>'check-circle','check-circle-o'=>'check-circle-o','check-square'=>'check-square','check-square-o'=>'check-square-o','child'=>'child','circle'=>'circle','circle-o'=>'circle-o','circle-o-notch'=>'circle-o-notch','circle-thin'=>'circle-thin','clock-o'=>'clock-o','cloud'=>'cloud','cloud-download'=>'cloud-download','cloud-upload'=>'cloud-upload','code'=>'code','code-fork'=>'code-fork','coffee'=>'coffee','cog'=>'cog','cogs'=>'cogs','comment'=>'comment','comment-o'=>'comment-o','comments'=>'comments','comments-o'=>'comments-o','compass'=>'compass','credit-card'=>'credit-card','crop'=>'crop','crosshairs'=>'crosshairs','cube'=>'cube','cubes'=>'cubes','cutlery'=>'cutlery','dashboard'=>'dashboard','database'=>'database','desktop'=>'desktop','dot-circle-o'=>'dot-circle-o','download'=>'download','edit'=>'edit','ellipsis-h'=>'ellipsis-h','ellipsis-v'=>'ellipsis-v','envelope'=>'envelope','envelope-o'=>'envelope-o','envelope-square'=>'envelope-square','eraser'=>'eraser','exchange'=>'exchange','exclamation'=>'exclamation','exclamation-circle'=>'exclamation-circle','exclamation-triangle'=>'exclamation-triangle','external-link'=>'external-link','external-link-square'=>'external-link-square','eye'=>'eye','eye-slash'=>'eye-slash','fax'=>'fax','female'=>'female','fighter-jet'=>'fighter-jet','file-archive-o'=>'file-archive-o','file-audio-o'=>'file-audio-o','file-code-o'=>'file-code-o','file-excel-o'=>'file-excel-o','file-image-o'=>'file-image-o','file-movie-o'=>'file-movie-o','file-pdf-o'=>'file-pdf-o','file-photo-o'=>'file-photo-o','file-picture-o'=>'file-picture-o','file-powerpoint-o'=>'file-powerpoint-o','file-sound-o'=>'file-sound-o','file-video-o'=>'file-video-o','file-word-o'=>'file-word-o','file-zip-o'=>'file-zip-o','film'=>'film','filter'=>'filter','fire'=>'fire','fire-extinguisher'=>'fire-extinguisher','flag'=>'flag','flag-checkered'=>'flag-checkered','flag-o'=>'flag-o','flash'=>'flash','flask'=>'flask','folder'=>'folder','folder-o'=>'folder-o','folder-open'=>'folder-open','folder-open-o'=>'folder-open-o','frown-o'=>'frown-o','gamepad'=>'gamepad','gavel'=>'gavel','gear'=>'gear','gears'=>'gears','gift'=>'gift','glass'=>'glass','globe'=>'globe','graduation-cap'=>'graduation-cap','group'=>'group','hdd-o'=>'hdd-o','headphones'=>'headphones','heart'=>'heart','heart-o'=>'heart-o','history'=>'history','home'=>'home','image'=>'image','inbox'=>'inbox','info'=>'info','info-circle'=>'info-circle','institution'=>'institution','key'=>'key','keyboard-o'=>'keyboard-o','language'=>'language','laptop'=>'laptop','leaf'=>'leaf','legal'=>'legal','lemon-o'=>'lemon-o','level-down'=>'level-down','level-up'=>'level-up','life-bouy'=>'life-bouy','life-ring'=>'life-ring','life-saver'=>'life-saver','lightbulb-o'=>'lightbulb-o','location-arrow'=>'location-arrow','lock'=>'lock','magic'=>'magic','magnet'=>'magnet','mail-forward'=>'mail-forward','mail-reply'=>'mail-reply','mail-reply-all'=>'mail-reply-all','male'=>'male','map-marker'=>'map-marker','meh-o'=>'meh-o','microphone'=>'microphone','microphone-slash'=>'microphone-slash','minus'=>'minus','minus-circle'=>'minus-circle','minus-square'=>'minus-square','minus-square-o'=>'minus-square-o','mobile'=>'mobile','mobile-phone'=>'mobile-phone','money'=>'money','moon-o'=>'moon-o','mortar-board'=>'mortar-board','music'=>'music','navicon'=>'navicon','paper-plane'=>'paper-plane','paper-plane-o'=>'paper-plane-o','paw'=>'paw','pencil'=>'pencil','pencil-square'=>'pencil-square','pencil-square-o'=>'pencil-square-o','phone'=>'phone','phone-square'=>'phone-square','photo'=>'photo','picture-o'=>'picture-o','plane'=>'plane','plus'=>'plus','plus-circle'=>'plus-circle','plus-square'=>'plus-square','plus-square-o'=>'plus-square-o','power-off'=>'power-off','print'=>'print','puzzle-piece'=>'puzzle-piece','qrcode'=>'qrcode','question'=>'question','question-circle'=>'question-circle','quote-left'=>'quote-left','quote-right'=>'quote-right','random'=>'random','recycle'=>'recycle','refresh'=>'refresh','reorder'=>'reorder','reply'=>'reply','reply-all'=>'reply-all','retweet'=>'retweet','road'=>'road','rocket'=>'rocket','rss'=>'rss','rss-square'=>'rss-square','search'=>'search','search-minus'=>'search-minus','search-plus'=>'search-plus','send'=>'send','send-o'=>'send-o','share'=>'share','share-alt'=>'share-alt','share-alt-square'=>'share-alt-square','share-square'=>'share-square','share-square-o'=>'share-square-o','shield'=>'shield','shopping-cart'=>'shopping-cart','sign-in'=>'sign-in','sign-out'=>'sign-out','signal'=>'signal','sitemap'=>'sitemap','sliders'=>'sliders','smile-o'=>'smile-o','sort'=>'sort','sort-alpha-asc'=>'sort-alpha-asc','sort-alpha-desc'=>'sort-alpha-desc','sort-amount-asc'=>'sort-amount-asc','sort-amount-desc'=>'sort-amount-desc','sort-asc'=>'sort-asc','sort-desc'=>'sort-desc','sort-down'=>'sort-down','sort-numeric-asc'=>'sort-numeric-asc','sort-numeric-desc'=>'sort-numeric-desc','sort-up'=>'sort-up','space-shuttle'=>'space-shuttle','spinner'=>'spinner','spoon'=>'spoon','square'=>'square','square-o'=>'square-o','star'=>'star','star-half'=>'star-half','star-half-empty'=>'star-half-empty','star-half-full'=>'star-half-full','star-half-o'=>'star-half-o','star-o'=>'star-o','suitcase'=>'suitcase','sun-o'=>'sun-o','support'=>'support','tablet'=>'tablet','tachometer'=>'tachometer','tag'=>'tag','tags'=>'tags','tasks'=>'tasks','taxi'=>'taxi','terminal'=>'terminal','thumb-tack'=>'thumb-tack','thumbs-down'=>'thumbs-down','thumbs-o-down'=>'thumbs-o-down','thumbs-o-up'=>'thumbs-o-up','thumbs-up'=>'thumbs-up','ticket'=>'ticket','times'=>'times','times-circle'=>'times-circle','times-circle-o'=>'times-circle-o','tint'=>'tint','toggle-down'=>'toggle-down','toggle-left'=>'toggle-left','toggle-right'=>'toggle-right','toggle-up'=>'toggle-up','trash-o'=>'trash-o','tree'=>'tree','trophy'=>'trophy','truck'=>'truck','umbrella'=>'umbrella','university'=>'university','unlock'=>'unlock','unlock-alt'=>'unlock-alt','unsorted'=>'unsorted','upload'=>'upload','user'=>'user','users'=>'users','video-camera'=>'video-camera','volume-down'=>'volume-down','volume-off'=>'volume-off','volume-up'=>'volume-up','warning'=>'warning','wheelchair'=>'wheelchair','wrench'=>'wrench','file'=>'file','file-archive-o'=>'file-archive-o','file-audio-o'=>'file-audio-o','file-code-o'=>'file-code-o','file-excel-o'=>'file-excel-o','file-image-o'=>'file-image-o','file-movie-o'=>'file-movie-o','file-o'=>'file-o','file-pdf-o'=>'file-pdf-o','file-photo-o'=>'file-photo-o','file-picture-o'=>'file-picture-o','file-powerpoint-o'=>'file-powerpoint-o','file-sound-o'=>'file-sound-o','file-text'=>'file-text','file-text-o'=>'file-text-o','file-video-o'=>'file-video-o','file-word-o'=>'file-word-o','file-zip-o'=>'file-zip-o','circle-o-notch'=>'circle-o-notch','cog'=>'cog','gear'=>'gear','refresh'=>'refresh','spinner'=>'spinner','check-square'=>'check-square','check-square-o'=>'check-square-o','circle'=>'circle','circle-o'=>'circle-o','dot-circle-o'=>'dot-circle-o','minus-square'=>'minus-square','minus-square-o'=>'minus-square-o','plus-square'=>'plus-square','plus-square-o'=>'plus-square-o','square'=>'square','square-o'=>'square-o','bitcoin'=>'bitcoin','btc'=>'btc','cny'=>'cny','dollar'=>'dollar','eur'=>'eur','euro'=>'euro','gbp'=>'gbp','inr'=>'inr','jpy'=>'jpy','krw'=>'krw','money'=>'money','rmb'=>'rmb','rouble'=>'rouble','rub'=>'rub','ruble'=>'ruble','rupee'=>'rupee','try'=>'try','turkish-lira'=>'turkish-lira','usd'=>'usd','won'=>'won','yen'=>'yen','align-center'=>'align-center','align-justify'=>'align-justify','align-left'=>'align-left','align-right'=>'align-right','bold'=>'bold','chain'=>'chain','chain-broken'=>'chain-broken','clipboard'=>'clipboard','columns'=>'columns','copy'=>'copy','cut'=>'cut','dedent'=>'dedent','eraser'=>'eraser','file'=>'file','file-o'=>'file-o','file-text'=>'file-text','file-text-o'=>'file-text-o','files-o'=>'files-o','floppy-o'=>'floppy-o','font'=>'font','header'=>'header','indent'=>'indent','italic'=>'italic','link'=>'link','list'=>'list','list-alt'=>'list-alt','list-ol'=>'list-ol','list-ul'=>'list-ul','outdent'=>'outdent','paperclip'=>'paperclip','paragraph'=>'paragraph','paste'=>'paste','repeat'=>'repeat','rotate-left'=>'rotate-left','rotate-right'=>'rotate-right','save'=>'save','scissors'=>'scissors','strikethrough'=>'strikethrough','subscript'=>'subscript','superscript'=>'superscript','table'=>'table','text-height'=>'text-height','text-width'=>'text-width','th'=>'th','th-large'=>'th-large','th-list'=>'th-list','underline'=>'underline','undo'=>'undo','unlink'=>'unlink','angle-double-down'=>'angle-double-down','angle-double-left'=>'angle-double-left','angle-double-right'=>'angle-double-right','angle-double-up'=>'angle-double-up','angle-down'=>'angle-down','angle-left'=>'angle-left','angle-right'=>'angle-right','angle-up'=>'angle-up','arrow-circle-down'=>'arrow-circle-down','arrow-circle-left'=>'arrow-circle-left','arrow-circle-o-down'=>'arrow-circle-o-down','arrow-circle-o-left'=>'arrow-circle-o-left','arrow-circle-o-right'=>'arrow-circle-o-right','arrow-circle-o-up'=>'arrow-circle-o-up','arrow-circle-right'=>'arrow-circle-right','arrow-circle-up'=>'arrow-circle-up','arrow-down'=>'arrow-down','arrow-left'=>'arrow-left','arrow-right'=>'arrow-right','arrow-up'=>'arrow-up','arrows'=>'arrows','arrows-alt'=>'arrows-alt','arrows-h'=>'arrows-h','arrows-v'=>'arrows-v','caret-down'=>'caret-down','caret-left'=>'caret-left','caret-right'=>'caret-right','caret-square-o-down'=>'caret-square-o-down','caret-square-o-left'=>'caret-square-o-left','caret-square-o-right'=>'caret-square-o-right','caret-square-o-up'=>'caret-square-o-up','caret-up'=>'caret-up','chevron-circle-down'=>'chevron-circle-down','chevron-circle-left'=>'chevron-circle-left','chevron-circle-right'=>'chevron-circle-right','chevron-circle-up'=>'chevron-circle-up','chevron-down'=>'chevron-down','chevron-left'=>'chevron-left','chevron-right'=>'chevron-right','chevron-up'=>'chevron-up','hand-o-down'=>'hand-o-down','hand-o-left'=>'hand-o-left','hand-o-right'=>'hand-o-right','hand-o-up'=>'hand-o-up','long-arrow-down'=>'long-arrow-down','long-arrow-left'=>'long-arrow-left','long-arrow-right'=>'long-arrow-right','long-arrow-up'=>'long-arrow-up','toggle-down'=>'toggle-down','toggle-left'=>'toggle-left','toggle-right'=>'toggle-right','toggle-up'=>'toggle-up','arrows-alt'=>'arrows-alt','backward'=>'backward','compress'=>'compress','eject'=>'eject','expand'=>'expand','fast-backward'=>'fast-backward','fast-forward'=>'fast-forward','forward'=>'forward','pause'=>'pause','play'=>'play','play-circle'=>'play-circle','play-circle-o'=>'play-circle-o','step-backward'=>'step-backward','step-forward'=>'step-forward','stop'=>'stop','youtube-play'=>'youtube-play','adn'=>'adn','android'=>'android','apple'=>'apple','behance'=>'behance','behance-square'=>'behance-square','bitbucket'=>'bitbucket','bitbucket-square'=>'bitbucket-square','bitcoin'=>'bitcoin','btc'=>'btc','codepen'=>'codepen','css3'=>'css3','delicious'=>'delicious','deviantart'=>'deviantart','digg'=>'digg','dribbble'=>'dribbble','dropbox'=>'dropbox','drupal'=>'drupal','empire'=>'empire','facebook'=>'facebook','facebook-square'=>'facebook-square','flickr'=>'flickr','foursquare'=>'foursquare','ge'=>'ge','git'=>'git','git-square'=>'git-square','github'=>'github','github-alt'=>'github-alt','github-square'=>'github-square','gittip'=>'gittip','google'=>'google','google-plus'=>'google-plus','google-plus-square'=>'google-plus-square','hacker-news'=>'hacker-news','html5'=>'html5','instagram'=>'instagram','joomla'=>'joomla','jsfiddle'=>'jsfiddle','linkedin'=>'linkedin','linkedin-square'=>'linkedin-square','linux'=>'linux','maxcdn'=>'maxcdn','openid'=>'openid','pagelines'=>'pagelines','pied-piper'=>'pied-piper','pied-piper-alt'=>'pied-piper-alt','pied-piper-square'=>'pied-piper-square','pinterest'=>'pinterest','pinterest-square'=>'pinterest-square','qq'=>'qq','ra'=>'ra','rebel'=>'rebel','reddit'=>'reddit','reddit-square'=>'reddit-square','renren'=>'renren','share-alt'=>'share-alt','share-alt-square'=>'share-alt-square','skype'=>'skype','slack'=>'slack','soundcloud'=>'soundcloud','spotify'=>'spotify','stack-exchange'=>'stack-exchange','stack-overflow'=>'stack-overflow','steam'=>'steam','steam-square'=>'steam-square','stumbleupon'=>'stumbleupon','stumbleupon-circle'=>'stumbleupon-circle','tencent-weibo'=>'tencent-weibo','trello'=>'trello','tumblr'=>'tumblr','tumblr-square'=>'tumblr-square','twitter'=>'twitter','twitter-square'=>'twitter-square','vimeo-square'=>'vimeo-square','vine'=>'vine','vk'=>'vk','wechat'=>'wechat','weibo'=>'weibo','weixin'=>'weixin','windows'=>'windows','wordpress'=>'wordpress','xing'=>'xing','xing-square'=>'xing-square','yahoo'=>'yahoo','youtube'=>'youtube','youtube-play'=>'youtube-play','youtube-square'=>'youtube-square','ambulance'=>'ambulance','h-square'=>'h-square','hospital-o'=>'hospital-o','medkit'=>'medkit','plus-square'=>'plus-square','stethoscope'=>'stethoscope','user-md'=>'user-md','wheelchair'=>'wheelchair','angellist'=>'angellist','area-chart'=>'fa-area-chart','at'=>'at','bell-slash'=>'bell-slash','bell-slash-o'=>'bell-slash-o','bicycle'=>'bicycle','binoculars'=>'binoculars','birthday-cake'=>'birthday-cake','bus'=>'bus','calculator'=>'calculator','cc'=>'cc','cc-amex'=>'cc-amex','cc-discover'=>'cc-discover','cc-paypal'=>'cc-paypal','cc-stripe'=>'cc-stripe','cc-visa'=>'cc-visa','copyright'=>'copyright','eyedropper'=>'eyedropper','futbol-o'=>'futbol-o','google-wallet'=>'google-wallet','ils'=>'ils','ioxhost'=>'ioxhost','lastfm'=>'lastfm','lastfm-square'=>'lastfm-square','line-chart'=>'line-chart','meanpath'=>'meanpath','newspaper-o'=>'newspaper-o','paint-brush'=>'paint-brush','paypal'=>'paypal','pie-chart'=>'pie-chart','plug'=>'plug','shekel'=>'shekel','sheqel'=>'sheqel','slideshare'=>'slideshare','soccer-ball-o'=>'soccer-ball-o','toggle-off'=>'toggle-off','toggle-on'=>'toggle-on','trash'=>'trash','tty'=>'tty','twitch'=>'twitch','wifi'=>'wifi','yelp'=>'yelp');
        return apply_filters( 'wpex_get_awesome_icons', $icons );
    }
}

/**
 * Array of Font Icons for meta options
 *
 * @since 1.0.0
 * @return array
 */
if ( ! function_exists( 'wpex_get_meta_awesome_icons' ) ) { 
    function wpex_get_meta_awesome_icons() {
        $awesome_icons = wpex_get_awesome_icons();
        $return_array = array();
        foreach ( $awesome_icons as $awesome_icon ) {
            $return_array[] = array(
                'name'  => $awesome_icon,
                'value' => $awesome_icon
            );
        }
        return $return_array;
    }
}