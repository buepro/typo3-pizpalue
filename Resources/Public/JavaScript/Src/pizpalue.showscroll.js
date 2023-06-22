/*
 * Plugin PpShowScroll
 *
 * In case the content element forms part of an accordion or tab component the related component item will be
 * shown (if not already visible) and subsequently scrolled to.
 *
 * The plugin is instantiated once and attached to the body data. This allows to set new default values at any time.
 *
 * Currently just the scrollOffset is available as an option parameter.
 *
 * Usage:
 * Set new default scroll offset: document.getElementsByTagName('body')[0].ppShowScroll('setDefaults',{scrollOffset: 50});
 * Scroll to content element using default scroll offset: document.getElementById('#cUID').ppShowScroll();
 * Scroll to a content element with a different scroll offset: document.getElementById('#cUID').ppShowScroll({scrollOffset: 60});
 *
 */
;+function (window, document, undefined) {

    const pluginName = 'ppShowScroll';

    const plugin = {
        _name: pluginName,
        defaults: {
            // Offset used for that the element is not scrolled fully to the window border
            scrollOffset: 100,
        },
        options: {},
        // The element to be shown and scrolled to
        element: null,
        // Tab pane element
        tabPane: null,
        // Accordion collapse element
        accordionCollapse: null,

        /*
         * Depending on the content element type to be scrolled to it might be needed to prior show the content
         * element before scrolling to it. Dedicated processing methods are available for the different  content
         * types.
         */
        process: function (element, options) {
            this.options = this.defaults;
            if (typeof options === 'object') {
                this.options = { ...this.options, ...options };
            }
            this.element = element;
            if (this._isTabContent()) {
                this._processTabContent();
            } else if (this._isAccordionContent()) {
                this._processAccordionContent();
            } else {
                this._scroll();
            }
        },

        _setDefaults: function (defaults) {
            this.defaults = {...this.defaults, ...defaults};
        },

        /*
         * Checks whether the content is a tab content element (having class .tab-pane) or is embedded to one.
         * In case a tab content element can be identified the plugin property tabPane is set.
         *
         * @return boolean
         */
        _isTabContent: function () {
            this.tabPane = this.element;
            if (this.tabPane.classList.contains('tab-pane')) {
                return true;
            }
            this.tabPane = this.element.closest('.tab-content, .tab-pane');
            return !!this.tabPane;
        },

        /*
         * Ensures the tab content is visible. When it is it will be scrolled to.
         */
        _processTabContent: function () {
            if (!this.tabPane) return;
            const ariaControls = this.tabPane.getAttribute('id');
            const tab = document.querySelector('[aria-controls=' + ariaControls + ']');
            if (!tab.classList.contains('active')) {
                tab.addEventListener('shown.bs.tab', this._handleTabShown);
                bootstrap.Tab.getOrCreateInstance(tab).show();
            } else {
                this._scroll();
            }
        },

        _handleTabShown: function (event) {
            event.target.removeEventListener('shown.bs.tab', this._handleTabShown);
            plugin._scroll();
        },

        /*
         * Checks whether the content is an accordion content element (having class .accordion-collapse) or is
         * embedded to one. In case an accordion content element can be identified the plugin property
         * accordionCollapse is set.
         *
         * @return boolean
         */
        _isAccordionContent: function () {
            this.accordionCollapse = this.element;
            if (this.accordionCollapse && this.accordionCollapse.classList.contains('accordion-collapse')) {
                return true;
            }
            this.accordionCollapse = this.element.closest('.accordion-item, .accordion-collapse');
            if (this.accordionCollapse && this.accordionCollapse.classList.contains('accordion-collapse')) {
                return true;
            }
            this.accordionCollapse = null;
            return false;
        },

        /*
         * Ensures the accordion content is visible. When it is it will be scrolled to.
         */
        _processAccordionContent: function () {
            if (!this.accordionCollapse) return;
            if (!this.accordionCollapse.classList.contains('show')) {
                this.accordionCollapse.addEventListener('shown.bs.collapse', this._handleAccordionContentShown);
                bootstrap.Collapse.getOrCreateInstance(this.accordionCollapse).show();
            } else {
                this._scroll();
            }
        },

        _handleAccordionContentShown: function (event) {
            event.target.removeEventListener('shown.bs.collapse', this._handleAccordionContentShown);
            plugin._scroll();
        },

        /*
         * Scroll to the content element defined by this.element.
         * In case fix positioned elements like the top navigation and cookie consent dialog are present the scroll
         * distance is adjusted. As well the plugins property scrollOffset is taken into account too.
         */
        _scroll: function () {
            if (!this.element) {
                return;
            }
            let targetOffset = this.element.getBoundingClientRect().top + window.scrollY - this.options.scrollOffset;
            const navbar = document.querySelector('.navbar-fixed-top');
            if (navbar && targetOffset !== 0) {
                targetOffset -= navbar.getBoundingClientRect().height;
            }
            const cookieconsent = document.querySelector('#cookieconsent .cc-window.cc-top:not(.cc-left):not(.cc-right):not(.cc-invisible)');
            if (cookieconsent && targetOffset !== 0) {
                targetOffset -= cookieconsent.getBoundingClientRect().height;
            }
            this.element.setAttribute('tabindex', '-1');
            this.element.focus();
            scroll({ top: targetOffset, behavior: "smooth" });
        },
    };

    /*
     * Plugin
     */
    Element.prototype[pluginName] = function (options) {
        const args = arguments;

        if (typeof options === 'string' && options === 'setDefaults' && args[1] && typeof args[1] === 'object') {
            plugin._setDefaults(options);
            return this;
        }

        if (typeof options === 'object') {
            plugin.process(this, options);
            return this;
        }
        plugin.process(this);
        return this;
    };

    /*
     * Backward compatibility
     * @deprecated since version 14, will be removed in 16
     */
    if (typeof Element.prototype.showAndScroll === undefined) {
        Element.prototype.showAndScroll = (options) => {
            console.log('WARNING: You are using the deprecated plugin "ShowAndScroll" from the TYPO3 extension ' +
                'pizpalue. Please update the code to ensure smooth operation.');
            return Element.prototype.ppShowScroll(options);
        }
    }

}(window, document);
