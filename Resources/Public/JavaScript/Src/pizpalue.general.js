;
/**
 * Module pp
 *
 * @see https://addyosmani.com/resources/essentialjsdesignpatterns/book/#highlighter_119636
 */
if ( typeof pp !== 'undefined' ) {
    alert('JS conflict! The variable pizpalue is already in use. Please check your libraries.');
} else {
    var pp = (function () {
        return {
            /**
             * Return value from a key (name) in the url
             *
             * @see davidwalsh.name/query-string-javascript
             */
            getUrlParameter: function (name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            },

            /**
             * Replacement for jQuery $()
             * @param callback
             */
            domReady: function (callback) {
                if (String(document.readyState) !== "loading") callback();
                else document.addEventListener("DOMContentLoaded", callback);
            },

            /**
             * Replacement for jQuery el.parents()
             *
             * $(el).parents(selector); => pp.parents(el, selector);
             */
            parents: function (el, selector) {
                const parents = [];
                while ((el = el.parentNode) && el !== document) {
                    if (!selector || el.matches(selector)) parents.unshift(el);
                }
                return parents;
            }
        }
    }) ();
}

/**
 * jQuery section
 */
+function ($) {

    /**
     * Size content elements horizontal to obtain homogeneous look.
     */
    $(function () {
        /**
         * Size the element height to the embedding row height (e.g. a content element in a column will be as high as
         * the row it belongs to). the class pp-row-height is used for that purpose.
         *
         * Currently just one nesting level is supported.
         */
        function sizeToRowHeight() {
            // get rows
            var $rows = $('.pp-row-height').closest('.row');
            $rows.each(function () {
                var maxHeight = 0;

                // reset heights
                var $elements = $('.pp-row-height', $(this)).css('height', 'auto');

                // get max height
                $elements.each(function () {
                    var $this = $(this);
                    if ($this.height() > maxHeight) maxHeight = $this.height();
                });

                // set max height
                $elements.height(maxHeight);
            });
        }
        if ($('.pp-row-height').length) {
            $(window).on('load,resize', sizeToRowHeight);
            $('img').on('load', sizeToRowHeight);
            sizeToRowHeight();
        }
    });

    /**
     * Dataprotection label
     */
    $(function () {
        var label;
        // Backward compatibility
        label = $('#idGeneralContactForm-idDataprotectionMultiCheckbox > div label');
        if ( label.length == 0 ) {
            // In case it is a checkbox-multiple
            label = $('.pp-dataprotection > div label');
        }
        if ( label.length == 0 ) {
            // In case it is a checkbox (single)
            label = $('.pp-dataprotection > label');
        }
        if ( label.length > 0 ) {
            var html = $('.pp-label-dataprotection p').html();
            label.find('> span').html(html);
        }
    });

    /**
     * Add main menu state to header
     */
    $(function () {
        $('#mainnavigation')
            .on('show.bs.collapse', function () { $('#page-header').addClass('pp-dropdown-active'); })
            .on('hidden.bs.collapse', function () { $('#page-header').removeClass('pp-dropdown-active'); });
    });

    /**
     * Link parent frame to url defined with anchor using the class `pp-link-frame`
     */
    $('.pp-extend-link').each(function () {
        var $this = $(this);
        if ($this.attr('href')) {
            var classAttr = $this.attr('class');
            var targetClass = 'frame-container';
            var match = classAttr.match(/ppc-el-([\w|-]+)/);
            if ( match && match[1] ) targetClass = match[1];
            $this.closest('.' + targetClass)
                .css('cursor', 'pointer')
                .click(function () {
                    window.location.href = $this.attr('href')
                });
        }
    })

    /**
     * Bootstrap popovers
     * ------------------
     * Ensures just one popover is open at a time and closes all popovers on clicking outside a popover.
     * Works with popovers bound to elements having the class pp-popover. Example:
     *
     * <a class="pp-popover" data-toggle="popover" data-trigger="click" title="Title" data-content="Content">Text</a>
     */
    $(function () {
        var $popovers = $('.pp-popover');
        if ($popovers.length) {
            // Just allow one popover to be active at a time
            $popovers.click(function () {
                if (!$(this).hasClass('ppc-active')) {
                    $('.pp-popover.ppc-active').popover('hide').removeClass('ppc-active');
                }
                $(this).toggleClass('ppc-active');
            });
            // Close popover on clicking outside
            $(document).click(function (event) {
                var $target = $(event.target),
                    isOutsideClick = $target.parents('.popover').length === 0;
                isOutsideClick = isOutsideClick && !$target.hasClass('pp-popover');
                if ( isOutsideClick) {
                    $('.pp-popover').popover('hide').removeClass('ppc-active');
                }
            })
        }
    });

}(jQuery);

