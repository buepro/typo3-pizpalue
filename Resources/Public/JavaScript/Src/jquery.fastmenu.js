+function ( $, window, document, undefined ) {

    var pluginName = 'fastMenu',
        defaults = {
            animationDuration: 0.7,
        };

    function Plugin( element, options ) {
        this.element = element;
        this.options = $.extend( {}, defaults, options );
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    Plugin.prototype.init = function () {
        this._setHeight();
        this._attachEventHandlers();
    };

    Plugin.prototype._setHeight = function () {
        var maxHeight = 0;
        $('.pp-fm-content .pp-fm-item', this.element).each( function () {
            var height = $(this).height();
            if (height > maxHeight) maxHeight = height;
        });
        maxHeight += 'px';
        $(this.element).css('min-height',maxHeight);
    };

    Plugin.prototype._attachEventHandlers = function () {
        $('.pp-fm-handle', this.element).click(function () {
            $(this).parent().toggleClass('pp-minimize');
        });
        $('.pp-fm-contenticon', this.element).click(function () {
            var id = '#' + $(this).attr('data-pp-fm-content');
            var $content = $(id);
            if ($content.hasClass('pp-show')) {
                $content.toggleClass('pp-show');
            } else {
                $content.siblings().removeClass('pp-show');
                $content.toggleClass('pp-show');
            }
        });
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