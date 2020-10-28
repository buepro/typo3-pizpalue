/**
 * PopoverLinkBrowser
 *
 * @see \Buepro\Pizpalue\Cms\Recordlist\LinkHandler\PopoverLinkHandler
 */
define(['jquery', 'TYPO3/CMS/Recordlist/LinkBrowser'], function($, LinkBrowser) {
    var ppPopover = {};
    var pp = {
        /**
         * PHP's nl2br
         * @link https://locutus.io/php/strings/nl2br/
         */
        nl2br: function (str, isXhtml) {
            // Some latest browsers when str is null return and unexpected null value
            if (typeof str === 'undefined' || str === null) {
                return ''
            }
            // Adjust comment to avoid issue on locutus.io display
            var breakTag = (isXhtml || typeof isXhtml === 'undefined') ? '<br ' + '/>' : '<br>'
            return (str + '')
                .replace(/(\r\n|\n\r|\r|\n)/g, breakTag)
        },
        /**
         * PHP' urlencode
         * @link https://locutus.io/php/url/urlencode/
         */
        urlencode: function (str) {
            str = (str + '')
            return encodeURIComponent(str)
                .replace(/!/g, '%21')
                .replace(/'/g, '%27')
                .replace(/\(/g, '%28')
                .replace(/\)/g, '%29')
                .replace(/\*/g, '%2A')
                .replace(/~/g, '%7E')
                .replace(/%20/g, '+')
        }
    };

    ppPopover.createLink = function(event) {
        event.preventDefault();
        const href = $(event.currentTarget).find('[name="lhref"]').val();
        let content = pp.nl2br($(event.currentTarget).find('[name="lcontent"]').val());
        let link = 't3://pppopover?href=' + pp.urlencode(href) + '&content=' + pp.urlencode(content);
        LinkBrowser.finalizeFunction(link);
    };

    ppPopover.initialize = function() {
        $('#pp-popoverform').on('submit', ppPopover.createLink)
    };

    $(ppPopover.initialize);

    return ppPopover;
});
