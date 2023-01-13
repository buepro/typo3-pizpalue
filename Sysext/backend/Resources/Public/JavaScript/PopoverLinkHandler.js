/**
 * PopoverLinkBrowser
 *
 * @see \Buepro\Pizpalue\Sysext\Recordlist\LinkHandler\PopoverLinkHandler
 */
define(['TYPO3/CMS/Recordlist/LinkBrowser'], function(LinkBrowser) {
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
        const href = event.currentTarget.querySelector('[name="lhref"]').value;
        let content = pp.nl2br(event.currentTarget.querySelector('[name="lcontent"]').value);
        let link = 't3://pppopover?href=' + pp.urlencode(href) + '&content=' + pp.urlencode(content);
        LinkBrowser.finalizeFunction(link);
    };

    ppPopover.initialize = function() {
        document.getElementById('pp-popoverform').addEventListener('submit', ppPopover.createLink)
    };

    const ready = (callback) => {
        if (document.readyState !== "loading") callback();
        else document.addEventListener("DOMContentLoaded", callback);
    }

    ready(() => {
        ppPopover.initialize();
    });

    return ppPopover;
});
