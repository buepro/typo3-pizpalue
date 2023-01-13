/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

import LinkBrowser
    from "@typo3/backend/link-browser.js";

/**
 * Module: @buepro/pizpalue/sysext/backend/popover-link-handler.js
 */
class PopoverLinkHandler {
    constructor() {
        document.getElementById('pp-popoverform').addEventListener('submit', event => {
            event.preventDefault();
            const href = event.currentTarget.querySelector('[name="lhref"]').value;
            let content = this.nl2br(event.currentTarget.querySelector('[name="lcontent"]').value);
            let link = 't3://pppopover?href=' + this.urlencode(href) + '&content=' + this.urlencode(content);
            LinkBrowser.finalizeFunction(link);
        });
    }

    /**
     * PHP's nl2br
     * @link https://locutus.io/php/strings/nl2br/
     */
    nl2br(str, isXhtml) {
        // Some latest browsers when str is null return and unexpected null value
        if (typeof str === 'undefined' || str === null) {
            return ''
        }
        // Adjust comment to avoid issue on locutus.io display
        var breakTag = (isXhtml || typeof isXhtml === 'undefined') ? '<br ' + '/>' : '<br>'
        return (str + '')
            .replace(/(\r\n|\n\r|\r|\n)/g, breakTag)
    }

    /**
     * PHP' urlencode
     * @link https://locutus.io/php/url/urlencode/
     */
    urlencode(str) {
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
}

export default new PopoverLinkHandler();
