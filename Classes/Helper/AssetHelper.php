<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Helper;

use TYPO3\CMS\Core\Page\AssetCollector;

class AssetHelper
{
    private AssetCollector $assetCollector;

    public function __construct(AssetCollector $assetCollector)
    {
        $this->assetCollector = $assetCollector;
    }

    public function includeAnimateCss(): void
    {
        /** @extensionScannerIgnoreLine */
        $this->assetCollector->addStyleSheet(
            'ppAnimateCss',
            'EXT:pizpalue/Resources/Public/Contrib/animate.css/animate.min.css'
        );
    }

    public function includeJosh(): void
    {
        $this->assetCollector->addJavaScript(
            'ppJosh',
            'EXT:pizpalue/Resources/Public/Contrib/josh.js/dist/josh.pp.min.js'
        );
    }

    public function includeTwikito(): void
    {
        $this->assetCollector->addJavaScript(
            'ppTwikitoOnscroll',
            'EXT:pizpalue/Resources/Public/Contrib/Twikito/onscroll-effect/dist/onscroll-effect.min.js'
        );
    }
}
