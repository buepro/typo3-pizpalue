;
/**
 * Pizpalue utility module
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
             * Capitalize first letter
             *
             * foo => Foo
             */
            ucfirst: (s) => (s && s[0].toUpperCase() + s.slice(1)) || "",

            /**
             * Replacement for jQuery $()
             * @param callback
             */
            domReady: function (callback) {
                if (String(document.readyState) !== "loading") callback();
                else document.addEventListener("DOMContentLoaded", callback);
            },

            /**
             * Replacement for jQuery parents function
             *
             * $(el).parents(selector); => pp.parents(el, selector);
             */
            parents: function (el, selector) {
                const parents = [];
                while ((el = el.parentNode) && el !== document) {
                    if (!selector || el.matches(selector)) parents.unshift(el);
                }
                return parents;
            },

            /**
             * Replacement for jQuery outerWith(true) function
             *
             * $(el).outerWidth(true); => pp.outerWidth(el);
             */
            outerWidth: function (el) {
                const style = getComputedStyle(el);
                return (
                    el.getBoundingClientRect().width +
                    parseFloat(style.getPropertyValue('marginLeft') || '0') +
                    parseFloat(style.getPropertyValue('marginRight') || '0')
                );
            },

            /**
             * Replacement for jQuery outeHeight(true) function
             *
             * $(el).outerHeight(true); => pp.outerHeight(el);
             */
            outerHeight: function (el) {
                const style = getComputedStyle(el);
                return (
                    el.getBoundingClientRect().height +
                    parseFloat(style.getPropertyValue('marginTop') || '0') +
                    parseFloat(style.getPropertyValue('marginBottom') || '0')
                );
            }
        }
    }) ();
}
