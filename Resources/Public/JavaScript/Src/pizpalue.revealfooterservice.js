+function () {
    /**
     * Applies an effect to the footer in a way that it is getting unrevealed while the user scrolls down hence giving
     * the impression a curtain is lifted. The effect is achieved by applying a padding to the bottom from the framing
     * container (.body-bg).
     *
     * The effect is disabled in case the footer content couldn't be shown entirely (e.g. when its content is using
     * more than the entire viewport height).
     */
    const revealFooterService = {
        frame: null,
        header: null,
        footer: null,
        // cookie consent dialog
        ccDialog: null,

        get contentWindowHeight () {
            let windowHeight = window.innerHeight;
            // reduced height if the header is fix positioned
            if (this.header.style.position === 'fixed') {
                windowHeight -= this.header.offsetHeight;
            }
            // reduce height if cookie consent dialog is present, visible and fix positioned
            if (this.ccDialogFixed) {
                windowHeight -= this.ccDialog.offsetHeight;
            }
            return windowHeight;
        },

        /**
         * Returns true in case the cookie consent dialog is present and fix positioned
         *
         * @return {boolean}
         */
        get ccDialogFixed () {
            if (this.ccDialog === null) {
                return false;
            }
            let dialogFillsWidth = pp.outerWidth(this.ccDialog) >= pp.outerWidth(this.frame);
            return !this.ccDialog.classList.contains('cc-invisible')
                && !this.ccDialog.classList.contains('cc-static')
                && dialogFillsWidth;
        },

        init: function () {
            this.frame = document.querySelector('.body-bg');
            this.header = document.querySelector('.body-bg > header');
            this.footer = document.querySelector('.body-bg > footer');
            this.ccDialog = document.querySelector('.cc-window');
        },

        canReveal: function () {
            return this.footer !== null && this.contentWindowHeight > this.footer.offsetHeight;
        },

        update: function () {
            let newPadding = this.footer.getBoundingClientRect().height;
            if (this.canReveal()) {
                this.frame.classList.add('pp-reveal-footer');
            } else {
                this.frame.classList.remove('pp-reveal-footer');
                newPadding = 0;
            }
            // in case the cookie consent dialog is fix positioned on the bottom the cookieconsentservice already took
            // care about offsetting the border container (footer)
            if (this.ccDialogFixed && this.ccDialog.classList.contains('cc-bottom')) return;
            // add padding to framing container
            this.frame.style.paddingBottom = newPadding + 'px';
        },

    };

    pp.domReady(function () {
        revealFooterService.init();
        revealFooterService.update();
        window.addEventListener('resize', function () {
            revealFooterService.update();
        });
        window.addEventListener('buepro.ccs.framechange',function() {
            revealFooterService.update();
        });
        window.addEventListener('buepro.ccs.borderchange',function() {
            revealFooterService.update();
        });
    });
}();
