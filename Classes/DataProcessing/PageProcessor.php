<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\DataProcessing;

use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Adds css defined in the page record to the asset collector.
 */
class PageProcessor implements DataProcessorInterface, SingletonInterface
{
    /**
     * @inheritDoc
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        if (
            $cObj->getCurrentTable() !== 'pages' ||
            ($css = trim($cObj->data['tx_pizpalue_css'] ?? '')) === ''
        ) {
            return $processedData;
        }
        GeneralUtility::makeInstance(AssetCollector::class)
            ->addInlineStyleSheet('pizpalue-page', $css, [], ['priority' => true]);
        return $processedData;
    }
}
