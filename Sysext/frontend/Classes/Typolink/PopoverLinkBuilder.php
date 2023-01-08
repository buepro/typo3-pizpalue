<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Sysext\Frontend\Typolink;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Typolink\AbstractTypolinkBuilder;
use TYPO3\CMS\Frontend\Typolink\LinkResult;
use TYPO3\CMS\Frontend\Typolink\LinkResultInterface;

/**
 * PopoverLinkBuilder
 *
 * Hint: Is used from FE.
 *
 * @see \Buepro\Pizpalue\Sysext\Recordlist\LinkHandler\PopoverLinkHandler
 */
class PopoverLinkBuilder extends AbstractTypolinkBuilder
{
    /**
     * Modifies the content from the href attribute.
     *
     * @see \Buepro\Pizpalue\Sysext\Recordlist\LinkHandler\PopoverLinkHandler
     * @inheritdoc
     */
    public function build(array &$linkDetails, string $linkText, string $target, array $conf): LinkResultInterface
    {
        $url = '';
        if (isset($linkDetails['href'])) {
            $urlConf = [
                'typolink.' => [
                    'parameter' => $linkDetails['href'],
                    'returnLast' => 'url'
                ]
            ];
            /** @var ContentObjectRenderer $cObjRenderer (new instance to avoid overriding data from parent) */
            $cObjRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $url = $cObjRenderer->stdWrap('', $urlConf) ?? '';
            if ($linkDetails['href'] === 'void') {
                $url = 'javascript:void(0);';
            }
        }
        return (new LinkResult('pppopover', $url))
            ->withTarget($target)
            ->withLinkConfiguration($conf)
            ->withLinkText($linkText);
    }
}
