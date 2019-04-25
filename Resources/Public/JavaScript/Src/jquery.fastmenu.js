+function ( $, window, document, undefined ) {

    var pluginName = 'fastMenu',
        defaults = {
            animationDuration: 700,
            iconFontSize: '50px',
        };

    function Plugin( element, options ) {
        this.element = element;
        this.options = $.extend( {}, defaults, options );
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    Plugin.prototype.init = function () {
        this._initContent();
        this._attachEventHandlers();
    };

    Plugin.prototype._initContent = function () {
        // Moves the content right, not too far away
        var fastmenuWidth = 2 * parseInt(this.options.iconFontSize);
        $('.pp-fm-content .pp-fm-item', this.element).each( function () {
            var rightOffset = -$(this).outerWidth() - fastmenuWidth;
            $(this).css('right',rightOffset + 'px');
        });
        // Hides contents
        $('.pp-fm-content .pp-fm-item', this.element).css('display','none');
    };

    Plugin.prototype._hideContent = function ($content,onHidden,onHiddenArg){
        var $contentIcon = $('#' + $content.attr('data-pp-fm-contenticon'));
        var rightOffset = -$(this.element).outerWidth();
        $content.animate(
            { right: rightOffset },
            this.options.animationDuration,
            $.proxy(function () {
                $content.css('display','none').removeClass('pp-show');
                $contentIcon.removeClass('pp-active');
                if ( onHidden ) {
                    // This is passed through since nesting $.proxy doesn't work
                    onHidden(onHiddenArg,this);
                }
            },this)
        );
    };

    /**
     * Shows a content
     *
     * @param $content The content to be shown
     * @param plugin The plugin might be passed through when the function is called from within a $.proxy function call
     * @private
     */
    Plugin.prototype._showContent = function ($content,plugin) {
        var animationDuration = 700;
        if (plugin) {
            animationDuration = plugin.options.animationDuration;
        } else {
            animationDuration = this.options.animationDuration
        }
        var $contentIcon = $('#' + $content.attr('data-pp-fm-contenticon'));
        $content
            .css('display','block')
            .animate({right: '0'},animationDuration,function () {
                $content.addClass('pp-show');
                $contentIcon.addClass('pp-active');
            });
    };

    Plugin.prototype._contentIconClickHandler = function ( event ) {
        var $contentIcon = $(event.target);
        var $content = $('#' + $contentIcon.attr('data-pp-fm-content'));
        if ( $content.hasClass('pp-show') ) {
            // Hides selected content
            this._hideContent($content);
        } else {
            var $visibleContent = $('.pp-fm-content .pp-show',this.element);
            if ( $visibleContent.length ) {
                this._hideContent($visibleContent,this._showContent,$content)
            } else {
                this._showContent($content);
            }
        }
    };

    Plugin.prototype._attachEventHandlers = function () {
        // Toggles the icon group
        $('.pp-fm-handle', this.element).click(function () {
            $(this).parent().toggleClass('pp-minimize');
        });

        // Toggles the content
        $('.pp-fm-contenticon', this.element).click($.proxy(this._contentIconClickHandler,this));
    };

    $.fn[pluginName] = function ( options ) {
        var args = arguments;

        // Is the first parameter an object (options), or was omitted,
        // instantiate a new instance of the plugin.
        if ( options === undefined || typeof options === 'object' ) {
            return this.each(function () {
                if (!$.data(this, 'plugin_' + pluginName)) {
                    $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
                }
            });

            // If the first parameter is a string and it doesn't start
            // with an underscore or "contains" the `init`-function,
            // treat this as a call to a public method.
        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {

            // Cache the method call to make it possible to return a value
            var returns;

            this.each(function () {
                var instance = $.data(this,'plugin_' + pluginName);

                // Tests that there's already a plugin-instance
                // and checks that the requested public method exists
                if (instance instanceof Plugin && typeof instance[options] === 'function') {
                    var params = Array.prototype.slice.call( args, 1 );
                    returns = instance[options].apply(instance, params);
                }

                // Allow instances to be destroyed via the 'destroy' method
                if (options === 'destroy') {
                    $.data(this, 'plugin_' + pluginName, null);
                }
            });

            // If the earlier cached method gives a value back return the value,
            // otherwise return this to preserve chainability.
            return returns !== undefined ? returns : this;
        }
    }

}(jQuery, window, document);