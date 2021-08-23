/**
 * Plugin ppAddressGoogleMap
 *
 * @link https://github.com/jquery-boilerplate/jquery-boilerplate/wiki/Extending-jQuery-Boilerplate
 */
;(function ( $, window, document, undefined ) {

    var pluginName = 'ppAddressGoogleMap',
        defaults = {
            // JSON encoded addresses
            addresses: null,
            // Map center coordinates
            lat: 46.829,
            lng: 8.241,
            // Map zoom
            zoom: 8
        };

    function Plugin( element, options ) {
        this.element = element;
        this.options = $.extend( {}, defaults, options) ;
        this._defaults = defaults;
        this._name = pluginName;
        this.map = null;
        this.$template = null;
        this.init();
    }

    Plugin.prototype.init = function () {
        this._initMap();
        this._initTemplate();
        this._initAddresses();
        this._addAddresses();
    };

    Plugin.prototype._initMap = function () {
        if (!this.map) {
            var mapCenter = { lat: this.options.lat, lng: this.options.lng };
            this.map = new google.maps.Map(this.element, {
                zoom: this.options.zoom,
                center: mapCenter
            });
        }
    };

    Plugin.prototype._initTemplate = function () {
        if (!this.$template) {
            this.$template = $(this.element).siblings('.pp-ttaddress-maptemplate');
        }
    };

    Plugin.prototype._initAddresses = function () {
        var addresses = this.options.addresses;

        // Replaces the image field with the image uri and adds the uri to show details
        if ( addresses[0].image ) {
            var $dataPanel = $(this.element).siblings('.pp-ttaddress-mapdata');
            var i, $dataItem;
            for (i in addresses) {
                $dataItem = $('[data-pp-amd-uid="' + addresses[i].uid + '"]', $dataPanel);
                addresses[i]['image'] = $dataItem.attr('data-pp-amd-imageuri');
                addresses[i]['uri'] = $dataItem.attr('data-pp-amd-uri');
            }
        }
    };

    Plugin.prototype._addMarker = function ( title, position, content, uid ) {
        var infowindow = new google.maps.InfoWindow({
            content: content
        });
        var marker = new google.maps.Marker({
            position: position,
            map: this.map,
            title: title
        });
        marker.addressUid = uid;
        marker.addListener('click', $.proxy( function() {
            infowindow.open(this.map, marker);
            setTimeout(
                function () {
                    var $img = $('[data-pp-amt-uid="' + marker.addressUid + '"] img');
                    $img.attr('src',$img.attr('data-pp-amt'));
                }, 50);
        }, this ));
    };

    Plugin.prototype._addAddresses = function () {
        var addresses = this.options.addresses;
        var address, title, position, content, i;
        for (i in addresses) {
            address = addresses[i];
            this._addMarker(
                this._getTitle(address),
                {
                    lat: parseFloat(address['latitude']),
                    lng: parseFloat(address['longitude'])
                },
                this._getContent(address),
                address['uid']
            );
        }
    };

    Plugin.prototype._getTitle = function ( address ) {
        var field = $(this.$template).attr('data-pp-amt-markertitle');
        return address[field];
    };

    Plugin.prototype._getContent = function ( address ) {
        var $content = this.$template.clone().css('display','block');

        // Sets address uid
        $('.pp-amt-wrap',$content).attr('data-pp-amt-uid', address['uid']);

        // Sets image data attribute
        if ( address.image ) {
            $('.pp-amt-image',$content).attr('data-pp-amt',address.image);
        }

        // Sets address content
        var field;
        for ( field in address ) {
            var $element = $('[data-pp-amt="' + field + '"]',$content);

            // Just sets the tag content in case it is empty
            // (Allows to define content by template. E.g. used for links)
            if ( !$element.html() ) {
                $element.html(address[field]);
            }

            // Sets links
            if ( $element.attr('href') === '#' ) {
                $element.attr('href', address[field]);
                if ( field === 'email' ) {
                    $element.attr('href','mailto:' + address[field]);
                }
                if ( field === 'www' ) {
                    var www = address[field];
                    if ( www.indexOf('http') === -1 ) {
                        www = 'https://' + www;
                    }
                    $element.attr('href',www);
                }
            }
        }
        return $content.html();
    };

    $.fn[pluginName] = function ( options ) {
        var args = arguments;

        if (options === undefined || typeof options === 'object') {
            return this.each(function () {
                if (!$.data(this, 'plugin_' + pluginName)) {
                    $.data(this, 'plugin_' + pluginName, new Plugin( this, options ));
                }
            });
        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {

            // Cache the method call
            // to make it possible
            // to return a value
            var returns;

            this.each(function () {
                var instance = $.data(this, 'plugin_' + pluginName);

                // Tests that there's already a plugin-instance
                // and checks that the requested public method exists
                if (instance instanceof Plugin && typeof instance[options] === 'function') {

                    // Call the method of our plugin instance,
                    // and pass it the supplied arguments.
                    returns = instance[options].apply( instance, Array.prototype.slice.call( args, 1 ) );
                }

                // Allow instances to be destroyed via the 'destroy' method
                if (options === 'destroy') {
                    $.data(this, 'plugin_' + pluginName, null);
                }
            });

            // If the earlier cached method
            // gives a value back return the value,
            // otherwise return this to preserve chainability.
            return returns !== undefined ? returns : this;
        }
    };

}(jQuery, window, document));


/**
 * Google Callback function
 */
function initMap() {
    var event = document.createEvent('Event');
    event.initEvent('buepro.ttaddress.googlemap', true, true);
    window.dispatchEvent(event);
}
