/**
 * Inspired by bootstrap_package to scroll to content when requested by internal link or url.
 * A plugin ensures the content element of interest is visible and scrolled to.
 *
 * Additionally the "scroll to top" feature is defined here.
 */
;+function ($, window, document, undefined) {

    /**
     * Plugin showAndScroll
     *
     * In case the content element forms part of an accordion or tab component the related component item will be
     * shown (if not already visible) and subsequently scrolled to.
     *
     * The plugin is instantiated once and attached to the body data. This allows to set new default values at any time.
     *
     * Currently just the scrollOffset is available as an option parameter.
     *
     * Usage:
     * Set new default scroll offset: $('body').showAndScroll('setDefaults',{scrollOffset: 50});
     * Scroll to content element using default scroll offset: $('#cUID').showAndScroll();
     * Scroll to a content element with a different scroll offset: $('#cUID').showAndScroll({scrollOffset: 50});
     *
     */
    +function () {
        /**
         * Parameters
         */
        var pluginName = 'showAndScroll',
            defaults = {
                // Offset used for that the element is not scrolled fully to the window border
                scrollOffset: 100,
            };

        /**
         * Constructor
         */
        var Plugin = function (element) {
            this._name = pluginName;
            this.options = $.extend({}, defaults);
        };

        /**
         * Methods
         */
        Plugin.prototype = {
            /**
             * Initialize processing
             */
            initProcessing: function (element,options) {
                // The element list passed in by calling the plugin
                this.$element = element;
                // The element to be scrolled to
                this.$target = element.first();
                // Tab pane element
                this.$tabPane = null;
                // Accordion collapse element
                this.$accordionCollapse = null;
            },

            /**
             * Checks whether the content is a tab content element (having class .tab-pane) or is embedded to one.
             * In case a tab content element can be identified the plugin property $tabPane is set.
             *
             * @return boolean
             */
            isTabContent: function () {
                this.$tabPane = this.$target;
                if (this.$tabPane.length && this.$tabPane.hasClass('tab-pane')) {
                    return true;
                }
                this.$tabPane = this.$target.parentsUntil('.tab-content','.tab-pane').first();
                if (this.$tabPane.length && this.$tabPane.hasClass('tab-pane')) {
                    return true;
                }
                this.$tabPane = null;
                return false;
            },

            /**
             * Ensures the tab content is visible. When it is it will be scrolled to.
             */
            processTabContent: function () {
                if (!this.$tabPane) return;
                var ariaControls = this.$tabPane.attr('id');
                var $tab = $('[aria-controls=' + ariaControls + ']');
                if (!$tab.hasClass('active')) {
                    var $this = this;
                    $tab
                        .on('shown.bs.tab', function () {
                            $tab.off('shown.bs.tab');
                            $this.scroll();
                        })
                        .tab('show');
                } else {
                    this.scroll();
                }
            },

            /**
             * Checks whether the content is an accordion content element (having class .accordion-collapse) or is
             * embedded to one. In case an accordion content element can be identified the plugin property
             * $accordionCollapse is set.
             *
             * @return boolean
             */
            isAccordionContent: function () {
                this.$accordionCollapse = this.$target;
                if (this.$accordionCollapse.length && this.$accordionCollapse.hasClass('accordion-collapse')) {
                    return true;
                }
                this.$accordionCollapse = this.$target.parentsUntil('.accordion-item','.accordion-collapse').first();
                if (this.$accordionCollapse.length && this.$accordionCollapse.hasClass('accordion-collapse')) {
                    return true;
                }
                this.$accordionCollapse = null;
                return false;
            },

            /**
             * Ensures the accordion content is visible. When it is it will be scrolled to.
             */
            processAccordionContent: function () {
                if (!this.$accordionCollapse) return;
                if (!this.$accordionCollapse.hasClass('show')) {
                    var $this = this;
                    this.$accordionCollapse
                        .on('shown.bs.collapse',function () {
                            $this.$accordionCollapse.off('shown.bs.collapse');
                            $this.scroll();
                        })
                        .collapse('show');
                } else {
                    this.scroll();
                }
            },

            /**
             * Depending on the content element type to be scrolled to it might be needed to prior show the content
             * element before scrolling to it. Dedicated processing methods are available for the different  content
             * types.
             */
            process: function () {
                if (this.isTabContent()) {
                    this.processTabContent();
                } else if (this.isAccordionContent()) {
                    this.processAccordionContent();
                } else {
                    this.scroll();
                }
            },

            /**
             * Scroll to the content elemnet defined by this.$target.
             * In case fix positioned elements like the top navigation and cookie consent dialog are present the scroll
             * distance is adjusted. As well the plugins property scrollOffset is taken into account.
             */
            scroll: function () {
                if (this.$target.length) {
                    var targetOffset = this.$target.offset().top;
                    targetOffset -= this.options.scrollOffset;
                    // Reduce navbar height if it is fix positioned
                    var $navbar = $('.navbar-fixed-top');
                    if($navbar.length){
                        targetOffset -= $navbar.outerHeight();
                    }
                    // Reduce cookie consent height if it is on top fix positioned
                    var $cc = $('.cc-window');
                    if ($cc.length && $cc.hasClass('cc-top')
                        && !$cc.hasClass('cc-static') && !$cc.hasClass('cc-invisible')) {
                        targetOffset -= $cc.outerHeight();
                    }
                    targetOffset = targetOffset > 0 ? targetOffset : 0;
                    var $classTarget = this.$target;
                    if ($('> .frame-container',$classTarget).length) {
                        // select the content frame (otherwise styles like box-shadow aren't visible fully)
                        $classTarget = $('> .frame-container',$classTarget).first();
                    }
                    if ($classTarget.is('a')) {
                        // select sibling in case the target is an anchor
                        $classTarget = $classTarget.siblings().first();
                    }
                    $classTarget.addClass('scrolltarget');
                    window.setTimeout(function ($this) {
                        $this.removeClass('scrolltarget');
                    },2000,$classTarget);
                    $('html,body').animate({scrollTop: targetOffset}, 500);
                }
            },
        };

        /**
         * Plugin
         */
        $.fn[pluginName] = function (options) {
            var args = arguments;

            // Get instance
            if (!$.data(document.body,'plugin_' + pluginName)) {
                $.data(document.body,'plugin_' + pluginName, new Plugin(this));
            }
            var instance = $.data(document.body,'plugin_' + pluginName);

            // Save new defaults
            if (typeof options === 'string' && options === 'setDefaults' && args[1] && typeof args[1] === 'object') {
                $.extend(instance.options,args[1]);
                $.data(document.body,'plugin_' + pluginName, instance);
                return this;
            }

            // Call the processing method
            instance.initProcessing(this,options);
            instance.process();

            // Return the initial elements for chaining
            return this;
        };
    }();

    /**
     * Attach internal link handler
     */
    $('a[href*="#"]:not([href$="#"])').click(function() {
        if (location.pathname.replace(/^\//,'') === this.pathname.replace(/^\//,'')
            && location.hostname === this.hostname
            && $(this).data('toggle') === undefined
            && $(this).data('slide') === undefined) {
            var $target = $(this.hash);
            $target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');
            if ($target.length) {
                $target.showAndScroll();
                return false;
            }
        }
    });

    /**
     * Scroll to top
     */
    $('.scroll-top').on('click', function() {
        $(this).blur();
    });
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 300) {
            $('.scroll-top').addClass('scroll-top-visible');
        } else {
            $('.scroll-top').removeClass('scroll-top-visible');
        }
    });

    /**
     * Show content defined by url
     */
    $(function () {
        if (window.location.hash) {
            var $target = $(window.location.hash);
            if (!$target.length) return;
            $target.showAndScroll();
        }
    });


}(jQuery,window,document);