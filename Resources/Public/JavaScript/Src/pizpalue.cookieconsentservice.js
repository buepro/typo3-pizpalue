+function () {
    /**
     * Embeds the fixed positioned cookie consent dialog in a way that the original page content is shown entirely. This
     * is achieved by adding a padding to the framing container (.body-bg). In case the original page content contains
     * fixed positioned border elements (header or footer) a margin is applied to one of them hence the cookie consent
     * dialog doesn't overlap it anymore.
     *
     * Events:
     * buepro.ccs.framechange: Fired when the padding from the framing container changes
     * buepro.ccs.borderchange: Fired when the margin from a border element changes
     */
    if (document.getElementById('cookieconsent') === null) {
        return;
    }
    const cookieConsentService = {
        dialog: null,
        dialogPosition: 'top',
        frame: null,
        border: null,

        /**
         * Initialize the object
         */
        init: function () {
            this.dialog = document.querySelector('#cookieconsent .cc-window');
            if (this.dialog && this.dialog.classList.contains('cc-bottom')) {
                this.dialogPosition = 'bottom';
            }
            this.frame = document.querySelector('.body-bg');
            if (this.dialogPosition === 'top') {
                this.border = document.querySelector('.body-bg > header');
            } else {
                this.border = document.querySelector('.body-bg > footer');
            }
        },

        embeddingRequired: function () {
            const dialogFillsWidth = pp.outerWidth(this.dialog) >= pp.outerWidth(this.frame);
            return this.dialog !== null
                && !this.dialog.classList.contains('cc-invisible')
                && !this.dialog.classList.contains('cc-static')
                && dialogFillsWidth;
        },

        /**
         * Update the frame for that the fixed positioned cookie consent dialog fits in.
         * To embed the dialog a padding is added to the framing container (.body-bg). The padding added is as big as
         * the cookie dialog height and the adjacent border element height if it is fix positioned.
         *
         * In case the padding from the framing container changes an event will be dispatched to give other members the
         * chance to adapt to this change.
         *
         * @return void
         */
        updateFrame: function () {
            const borderStyle = getComputedStyle(this.border);
            // the height to fit the dialog
            let newPadding = pp.outerHeight(this.dialog);
            if (!this.embeddingRequired() || this.dialog.classList.contains('cc-invisible')) {
                newPadding = 0;
            }
            // the height from a fix positioned border element
            if(borderStyle['position'] === 'fixed') {
                newPadding += pp.outerHeight(this.border);
            }
            const previousPadding = parseFloat(getComputedStyle(this.frame)['padding-' + this.dialogPosition]);
            this.frame.style['padding' + pp.ucfirst(this.dialogPosition)] = newPadding + 'px';
            // notify about the change
            if (newPadding !== previousPadding) {
                const event = new Event('buepro.ccs.framechange', { bubbles: true, cancelable: true });
                window.dispatchEvent(event);
            }
        },

        /**
         * On the frames border fix positioned elements might be present. For that the cookie consent dialog doesn't
         * overlap those elements a margin is added to the border element next to the cookie consent dialog.
         *
         * In case the margin from the border element changes an event will be dispatched to give other members the
         * chance to adapt to this change.
         *
         * @return void
         */
        updateBorder: function () {
            const borderStyle = getComputedStyle(this.border);
            let newMargin = pp.outerHeight(this.dialog);
            if (!this.embeddingRequired() || this.dialog.classList.contains('cc-invisible')) {
                newMargin = 0;
            }
            // remove margin if the element is not fix positioned
            if(borderStyle['position'] !== 'fixed') {
                newMargin = 0;
            }
            const previousMargin = parseFloat(borderStyle['margin-' + this.dialogPosition]);
            // apply an effect in case the margin is removed
            this.border.style['margin-' + this.dialogPosition] = newMargin + 'px';
            // notify about the change
            if (newMargin !== previousMargin) {
                const event = new Event('buepro.ccs.borderchange', { bubbles: true, cancelable: true });
                window.dispatchEvent(event);
            }
        },

        /**
         * Update the dialog embedding
         *
         * @return void
         */
        update: function () {
            this.updateFrame();
            this.updateBorder();
        },

        /**
         * Call the update method with delay to ensure the dialog is in stationary state.
         * Upon calling this method the dialog might not have its final size due to the rendering not being completed
         * (e.g. due to animations or screen rotation).
         *
         * @see CookiePopup.prototype.fadeIn from cookieconsent.js
         */
        updateDelayed: function (delay) {
            delay = typeof delay !== 'undefined' ? delay : 40;
            window.setTimeout(() => {
                this.update();
            }, delay, this);
        },

        registerEventHandlers: function () {
            window.addEventListener('bk2k.cookie.popupopen', () => {
                cookieConsentService.updateDelayed();
            });

            window.addEventListener('bk2k.cookie.popupclose', () => {
                cookieConsentService.update();
            });

            const mediaOrientation = window.matchMedia('(orientation: portrait)');
            mediaOrientation.addEventListener('change', () => {
                cookieConsentService.updateDelayed(200);
            });
        },

    };

    /**
     * Start when DOM is ready (cookie consent dialog added to DOM)
     */
    pp.domReady(() => {
        /**
         * delay initialisation until cookie dialog updated class 'cc-invisible'
         *
         * @see CookiePopup.prototype.fadeIn from cookieconsent.js
         */
        window.setTimeout( () => {
            cookieConsentService.init();
            cookieConsentService.registerEventHandlers();
            if (!cookieConsentService.embeddingRequired()) return;
            cookieConsentService.update();
        },40);
    });

}();
