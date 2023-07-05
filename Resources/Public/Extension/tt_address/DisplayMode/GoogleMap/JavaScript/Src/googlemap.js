/**
 * Plugin ppAddressGoogleMap
 *
 * @link https://github.com/jquery-boilerplate/jquery-boilerplate/wiki/Extending-jQuery-Boilerplate
 */
;(function ( window, document, undefined ) {

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
        this.options = {...defaults, ...options};
        this._defaults = defaults;
        this._name = pluginName;
        this.map = null;
        this.template = null;
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
        if (!this.template) {
            this.template = this.element.parentElement.querySelector('.pp-ttaddress-maptemplate');
        }
    };

    Plugin.prototype._initAddresses = function () {
        const addresses = this.options.addresses;

        // Replaces the image field with the image uri and adds the uri to show details
        if ( addresses[0].image ) {
            const dataPanel = this.element.parentElement.querySelector('.pp-ttaddress-mapdata');
            let i, dataItem;
            for (i in addresses) {
                dataItem = dataPanel.querySelector('[data-pp-amd-uid="' + addresses[i].uid + '"]');
                addresses[i]['image'] = dataItem.dataset.ppAmdImageuri;
                addresses[i]['uri'] = dataItem.dataset.ppAmdUri;
            }
        }
    };

    Plugin.prototype._addMarker = function ( title, position, content, uid ) {
        const marker = new google.maps.Marker({ position: position, map: this.map, title: title });
        const infoWindow = new google.maps.InfoWindow({ content: content });
        marker.addressUid = uid;
        marker.addListener('click', () => {
            infoWindow.open(this.map, marker);
            setTimeout(
                () => {
                    const img = document.querySelector('[data-pp-amt-uid="' + marker.addressUid + '"] img');
                    img.setAttribute('src', img.dataset.ppAmt);
                },
                50
            );
        });
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
        const field = this.template.dataset.ppAmtMarkertitle;
        return address[field];
    };

    Plugin.prototype._getContent = function ( address ) {
        const content = this.template.cloneNode(true);
        content.style.display = 'block';

        // Sets address uid
        content.querySelector('.pp-amt-wrap').dataset.ppAmtUid = address['uid'];

        // Sets image data attribute
        if ( address.image ) {
            content.querySelector('.pp-amt-image').dataset.ppAmt = address.image;
        }

        // Sets address content
        let field;
        for ( field in address ) {
            let element = content.querySelector('[data-pp-amt="' + field + '"]');
            if ( !element ) {
                continue;
            }

            // Just sets the tag content in case it is empty
            // (Allows to define content by template. E.g. used for links)
            if ( !element.innerHTML ) {
                element.innerHTML = address[field];
            }

            // Sets links
            if ( element.getAttribute('href') === '#' ) {
                element.setAttribute('href', address[field]);
                if ( field === 'email' ) {
                    element.setAttribute('href','mailto:' + address[field]);
                }
                if ( field === 'www' ) {
                    // Strip tailing link target like '_blank'
                    let www = address[field].match(/^[\S]*/g)[0];
                    if ( www.indexOf('http') === -1 ) {
                        www = 'https://' + www;
                    }
                    element.setAttribute('href', www);
                }
            }
        }
        return content.innerHTML;
    };

    Element.prototype[pluginName] = function ( options ) {
        if (options === undefined || typeof options === 'object') {
            if (!this['plugin_' + pluginName]) {
                this['plugin_' + pluginName] = new Plugin( this, options );
            }
            return this;
        }
    };

}(window, document));


/**
 * Google Callback function
 */
function ppGoogleMapsInitMap() {
    const event = new Event('buepro.ttaddress.googlemap')
    window.dispatchEvent(event);
}
