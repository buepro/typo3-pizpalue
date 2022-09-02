/**
 * PLUGIN PpCategorizedContent
 * see https://github.com/joemottershaw/vanilla-js-plugin-boilerplate
 */
;(function (root, factory) {
    const plugin_name = 'PpCategorizedContent';

    // Universal module definition (amd, commonjs, browser)
    if (typeof define === 'function' && define.amd) {
        define([], factory(plugin_name));
    } else if (typeof exports === 'object') {
        module.exports = factory(plugin_name);
    } else {
        root[plugin_name] = factory();
    }
}((window || module || {}), function() {
    'use strict';

    /**
     * Constructor.
     * @param  {element}  listElement  The list element.
     * @param  {object}   options  The plugin options.
     * @return {void}
     */
    function Plugin(listElement, options) {
        const defaults = {
            itemHeights: {
                0: 300,
                575: 300,
                767: 300,
                991: 300,
                1199: 300
            },
        };
        const list = listElement;
        const settings = Object.assign(
            {},
            defaults,
            {itemHeights: JSON.parse(list.getAttribute('data-pp-heights'))},
            options
        );

        /**
         * @return {number}
         */
        const getClosedHeight = () => {
            let screenWidth = $(window).width(),
                result = settings.itemHeights[0],
                minWidth;
            for (minWidth in settings.itemHeights) {
                if(settings.itemHeights[minWidth] > 0 && screenWidth > parseInt(minWidth)) {
                    result = settings.itemHeights[minWidth];
                }
            }
            return + result;
        };

        /**
         * @return {void}
         */
        const initializeListItems = () => {
            list.querySelectorAll('.ppc-collapsible').forEach(element => {
                let closedHeight = getClosedHeight(),
                    button = element.querySelector('.ppc-more'),
                    toggleBarDisplay = "none";
                if (element.firstChild.getBoundingClientRect().height > closedHeight) {
                    toggleBarDisplay = "block";
                    if (button.classList.contains('open')) {
                        element.style.height = element.firstChild.getBoundingClientRect().height + 'px';
                    } else {
                        element.style.height = closedHeight + 'px';
                    }
                } else {
                    element.style.height = "auto";
                }
                element.querySelector('.ppc-more').style.display = toggleBarDisplay;
                element.querySelector('.ppc-bottom').style.display = toggleBarDisplay;
            })
        };

        /**
         * @return {void}
         */
        const addEventListeners = () => {
            let resizeTimeout;
            $(window).on('resize', function () {
                if (resizeTimeout !== undefined) {
                    window.cancelAnimationFrame(resizeTimeout);
                }
                resizeTimeout = window.requestAnimationFrame(function () {
                    initializeListItems;
                });
            });
            list.querySelector('img').addEventListener('load', (e) => {
                initializeListItems();
            });
            list.querySelectorAll('.ppc-more').forEach(element => {
                element.addEventListener('click', (e) => {
                    let button = e.currentTarget,
                        item = button.parentElement,
                        height = item.firstChild.getBoundingClientRect().height;
                    if (button.classList.contains('open')) {
                        item.style.height = getClosedHeight() + 'px';
                        item.querySelector('.ppc-bottom').style.display = 'block';
                        button.classList.remove('open');
                    } else {
                        item.style.height = height + 'px';
                        item.querySelector('.ppc-bottom').style.display = 'none';
                        button.classList.add('open');
                    }
                });
            });
        };

        initializeListItems();
        addEventListeners();
    }

    return Plugin;
}));

(function () {
    let lists = Array.prototype.slice.call(document.querySelectorAll('.pp-list-categorized-content'));
    lists.forEach(function (list) {
        new PpCategorizedContent(list);
    });
})();

