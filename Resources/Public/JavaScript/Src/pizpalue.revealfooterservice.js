+function ($) {
    /**
     * Applies an effect to the footer in a way that it is getting unrevealed while the user scrolls down hence giving
     * the impression a curtain is lifted. The effect is achieved by applying a padding to the bottom from the framing
     * container (.body-bg).
     *
     * The effect is disabled in case the footer content couldn't be shown entirely (e.g. when its content is using
     * more than the entire viewport height).
     */
    var revealFooterService = {
        $frame: null,
        $header: null,
        $footer: null,
        // cookie consent dialog
        $ccDialog: null,

        get contentWindowHeight () {
            var windowHeight = $(window).height();
            // reduced height if the header is fix positioned
            if (this.$header.css('position') === 'fixed') {
                windowHeight -= this.$header.outerHeight();
            }
            // reduce height if cookie consent dialog is present, visible and fix positioned
            if (this.ccDialogFixed()) {
                windowHeight -= this.$ccDialog.outerHeight();
            }
            return windowHeight;
        },

        /**
         * Returns true in case the cookie consent dialog is present and fix positioned
         * 
         * @return {boolean}
         */
        ccDialogFixed: function () {
            var dialogFillsWidth = this.$ccDialog.outerWidth(true) >= this.$frame.outerWidth(true);
            return this.$ccDialog.length > 0
                && !this.$ccDialog.hasClass('cc-invisible')
                && !this.$ccDialog.hasClass('cc-static')
                && dialogFillsWidth;
        },

        init: function () {
            this.$frame = $('.body-bg');
            this.$header = $('.body-bg > header');
            this.$footer = $('.body-bg > footer');
            this.$ccDialog = $('.cc-window');
        },

        canReveal: function () {
            var result = this.$footer.length === 1;
            return result && this.contentWindowHeight > this.$footer.outerHeight();
        },
        
        update: function () {
            var newPadding = this.$footer.height();
            if (this.canReveal()) {
                this.$frame.addClass('pp-reveal-footer');
            } else {
                this.$frame.removeClass('pp-reveal-footer');
                newPadding = 0;
            }
            // in case the cookie consent dialog is fix positioned on the bottom the cookieconsentservice already took
            // care about offsetting the border container (footer)
            if (this.ccDialogFixed() && this.$ccDialog.hasClass('cc-bottom')) return;
            // add padding to framing container
            this.$frame.css('padding-bottom',newPadding);
        },

    };

    $(function () {
        // dom ready
        $(function () {
            revealFooterService.init();
            revealFooterService.update();
            $(window).resize(function () {
                revealFooterService.update();
            });
            window.addEventListener('buepro.ccs.framechange',function() {
                revealFooterService.update();
            });
            window.addEventListener('buepro.ccs.borderchange',function() {
                revealFooterService.update();
            });
        });
    });
}(jQuery);