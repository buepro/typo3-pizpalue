+function (window, document, undefined) {

    const pluginName = 'fastMenu';

    function Plugin(element) {
        this.element = element;
        this._name = pluginName;
        this.init();
    }

    Plugin.prototype.init = function () {
        this._attachEventHandlers();
        this._loadView();
    };

    Plugin.prototype._hideContent = function (content){
        const contentIcon = document.getElementById(content.dataset.ppFmContenticon);
        content.classList.remove('pp-show');
        contentIcon.classList.remove('pp-active');
    };

    Plugin.prototype._showContent = function (content) {
        const contentIcon = document.getElementById(content.dataset.ppFmContenticon);
        content.classList.add('pp-show');
        contentIcon.classList.add('pp-active');
    };

    Plugin.prototype._contentIconClickHandler = function (event) {
        const
            contentIcon = event.target,
            content = document.getElementById(contentIcon.dataset.ppFmContent);
        if ( content.classList.contains('pp-show') ) {
            // Hides selected content
            this._hideContent(content);
        } else {
            const visibleContents = this.element.querySelectorAll('.pp-fm-content .pp-show');
            if ( visibleContents.length ) {
                visibleContents.forEach((visibleContent) => this._hideContent(visibleContent));
            }
            this._showContent(content);
        }
    };

    Plugin.prototype._toggleMenu = function () {
        this.element.classList.toggle('pp-minimize');
        this._saveView();
    }

    Plugin.prototype._attachEventHandlers = function () {
        // Toggles the icon group
        this.element.querySelectorAll('.pp-fm-handle').forEach((el) => {
            el.addEventListener('click', this._toggleMenu.bind(this));
        })

        // Toggles the content
        this.element.querySelectorAll('.pp-fm-contenticon').forEach((el) => {
            el.addEventListener('click', this._contentIconClickHandler.bind(this));
        })
    };

    /**
     * Loads the view by respecting the last state (minimized)
     *
     * @private
     */
    Plugin.prototype._loadView = function () {
        const minimized = Cookies.get('ppFastMenuMinimized');
        if (typeof minimized === 'undefined') {
            this._saveView();
        }
        if ( minimized === 'true' ) {
            this.element.classList.add('pp-minimize');
        } else {
            this.element.classList.remove('pp-minimize');
        }
    }

    Plugin.prototype._saveView = function () {
        const minimized = String(this.element.classList.contains('pp-minimize'));
        Cookies.set('ppFastMenuMinimized', minimized, { expires: 365 });
    }

    Element.prototype[pluginName] = function () {
        // Store plugin in element
        if (!this['plugin_' + pluginName]) {
            this['plugin_' + pluginName] = new Plugin(this);
        }
        return this;
    }

}(window, document);
