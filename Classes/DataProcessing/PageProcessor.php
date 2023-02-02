<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\DataProcessing;

use Buepro\Pizpalue\Helper\AssetHelper;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Adds the following css and js assets:
 * - css defined from the page record
 * - animate.css library
 * - josh scroll animation library
 * - twikito scroll animation library
 */
class PageProcessor implements DataProcessorInterface, SingletonInterface
{
    private ContentObjectRenderer $cObj;
    private array $processedData;
    private AssetHelper $assetHelper;

    /**
     * @inheritDoc
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        if ($cObj->getCurrentTable() !== 'pages') {
            return $processedData;
        }
        $this->cObj = $cObj;
        $this->processedData = $processedData;
        $this->assetHelper = GeneralUtility::makeInstance(AssetHelper::class);
        $this->processPageCss()->processAnimateCss()->processJosh()->processTwikito();
        return $this->processedData;
    }

    private function processPageCss(): self
    {
        if (($css = trim($this->cObj->data['tx_pizpalue_css'] ?? '')) === '') {
            return $this;
        }
        GeneralUtility::makeInstance(AssetCollector::class)
            ->addInlineStyleSheet('pizpalue-page', $css, [], ['priority' => true]);
        return $this;
    }

    private function processAnimateCss(): self
    {
        if ((bool)($this->processedData['pizpalue']['animation']['animateCss']['includeAlways'] ?? false)) {
            $this->assetHelper->includeAnimateCss();
        }
        return $this;
    }

    private function processJosh(): self
    {
        if ((bool)($this->processedData['pizpalue']['animation']['josh']['includeAlways'] ?? false)) {
            $this->assetHelper->includeJosh();
        }
        return $this;
    }

    private function processTwikito(): self
    {
        if ((bool)($this->processedData['pizpalue']['animation']['twikito']['includeAlways'] ?? false)) {
            $this->assetHelper->includeTwikito();
        }
        return $this;
    }
}
