 /**
 * Theme functions
 * Initialize all scripts and adds custom js
 *
 * @since 1.0.0
 *
 */
(function($) {
    'use strict';

    var wpexFunctions = {

        /**
         * Define cache var
         *
         * @since 1.0.0
         */
        cache: {},

        /**
         * Main Function
         *
         * @since 1.0.0
         */
        init: function() {
            this.cacheElements();
            this.bindEvents();
        },

        /**
         * Cache Elements
         *
         * @since 1.0.0
         */
        cacheElements: function() {
            this.cache = {
                $window   : $(window),
                $document : $(document),
                $isMobile : wpexLocalize.isMobile
            };
        },

        /**
         * Bind Events
         *
         * @since 1.0.0
         */
        bindEvents: function() {

            // Get sef
            var self = this;

            // Run on document ready
            this.cache.$document.on( 'ready', function() {
                self.coreFunctions();
                self.scrollTop();
                self.tabsWidget();
                self.lightbox();
                self.mobileToggle();
                self.equalHeights();
            } );

            // Run on Window Load
            this.cache.$window.on( 'load', function() {
                self.postSlider();
                self.equalHeightsUpdate();
            } );

        },

        /**
         * Main theme functions
         *
         * @since 1.0.0
         */
        coreFunctions: function() {
           
            // Add class to last pingback for styling purposes
            $( ".commentlist li.pingback" ).last().addClass( 'last' );

        },

        /**
         * Scroll top function
         *
         * @since 1.0.0
         */
        scrollTop: function() {
            $( 'div.scroll-top a' ).click( function() {
                $( 'html, body' ).animate( {
                    scrollTop   : 0
                }, 'fast' );
                return false;
            } );
        },

        /**
         * Widget tabs
         *
         * @since 1.0.0
         */
        tabsWidget: function() {
            $( '.wpex-tabs-widget-tabs a' ).on( this.cache.$isMobile ? 'touchstart' : 'click', function(event) {
                var $parentLi = $( this ).parent();
                if ( ! $parentLi.hasClass( 'active' ) ) {
                    $( 'ul.wpex-tabs-widget-tabs li' ).removeClass( 'active' );
                    $parentLi.addClass( 'active' );
                    var data = $(this).data( 'tab' );
                    $( '.wpex-tabs-widget-tab.active-tab' ).stop( true, true ).hide();
                    $(this).removeClass( 'active-tab' );
                    $(data).addClass( 'active-tab' );
                    $(data).show();
                }
                return false;
            } );
        },

        /**
         * Post Slider
         *
         * @since 1.0.0
         */
        postSlider: function() {
            
            if ( $.fn.lightSlider != undefined ) {
                var $slider = $( '.post-slider' );
                $slider.find( 'div' ).show();
                $slider.lightSlider( {
                    mode            : 'slide',
                    speed           : 700,
                    adaptiveHeight  : true,
                    item            : 1,
                    slideMargin     : 0,
                    loop            : false,
                    rtl             : wpexLocalize.isRTL,
                    prevHtml        : '<span class="fa fa-angle-left"></span>',
                    nextHtml        : '<span class="fa fa-angle-right"></span>',
                    onBeforeStart   : function( el ) {
                        $( '.slider-first-image-holder, .slider-preloader' ).hide();
                    }
                } );
            }

        },

        /**
         * Lightbox
         *
         * @since 1.0.0
         */
        lightbox: function() {

            if ( $.fn.magnificPopup != undefined ) {

                // Gallery lightbox
                $( '.wpex-lightbox-gallery' ).each( function() {
                    $(this).magnificPopup( {
                        delegate    : 'a.wpex-lightbox-item',
                        type        : 'image',
                        gallery     : {
                            enabled : true
                        }
                    } );
                } );

                if ( wpexLocalize.wpGalleryLightbox == true ) {

                    $( '.entry .gallery' ).each( function() {

                        // Check first item and see if it's linking to an image if so add lightbox
                        var firstItemHref = $(this).find( '.gallery-item a' ).first().attr( 'href' );
                        if ( /\.(jpg|png|gif)$/.test( firstItemHref ) ) {
                            $(this).magnificPopup( {
                                delegate    : '.gallery-item a',
                                type        : 'image',
                                gallery     : {
                                    enabled : true
                                }
                            } );
                        }

                    } );

                }

                // Auto add lightbox to entry images
                $( '.entry a:has(img)' ).each( function() {
                    var $this   = $(this),
                        $img    = $this.find( 'img' ),
                        $src    = $img.attr( 'src' ),
                        $ref    = $this.attr( 'href' );
                    if( $src == $ref ) {
                        $this.magnificPopup( {
                            type    : 'image'
                        } );
                    }
                } );

            }

        },

        /**
         * Match Height functions
         *
         * @since 1.0.0
         */
        equalHeights: function() {

            // Equal heights for the sidebar and content
            $( '.sidebar-container, .site-main' ).addClass( 'wpex-match-height' );
            if ( $.fn.matchHeight != undefined ) {
                $( '.wpex-match-height' ).matchHeight();
            }
            
        },

        /**
         * Match Height Re-Process
         *
         * @since 1.0.0
         */
        equalHeightsUpdate: function() {

            if ( $.fn.matchHeight != undefined ) {
               $.fn.matchHeight._update();
            }
            
        },

        /**
         * Mobile Toggle
         *
         * @since 1.0.0
         */
        mobileToggle: function() {

            $( '.sidebar-toggle' ).on( this.cache.$isMobile ? 'touchstart' : 'click', function(event) {
                $( '.sidebar-container .hide-on-mobile' ).toggleClass( 'visible' );
                $( this ).find( '.fa' ).toggleClass( 'fa-bars fa-close' );
            } ); 

        },

    }; // END wpexFunctions

    // Get things going
    wpexFunctions.init();

} ) ( jQuery );