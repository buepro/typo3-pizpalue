<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\DataProcessing;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Creates a list of scroll menu items from content elements having `tx_pizpalue_scroll_navigation_enable` activated.
 * All configuration values are obtained by the stdWrap function where for the property `contentElementPid` the
 * page data and for the remaining properties the corresponding content element data is used. In case the
 * `contentElementPid` or the `as` property are absent they can be set through the `pizpalue.menu.scroll` path.
 *
 * A scroll menu item consists of the following structure:
 *
 * $scrollMenuItem = [
 *     'data' => [],
 *     'link' => '/'
 *     'pageUid' => 1,
 *     'section' => 'c51',
 *     'title' => 'Menu item X',
 *     'titleTag' => 'Title for menu item X',
 * ]
 *
 * Usage:
 * 10 = Buepro\Pizpalue\DataProcessing\ScrollMenuProcessor
 * 10 {
 *     contentElementPid.field = uid
 *     title.field = tx_pizpalue_scroll_navigation_title // header
 *     titleTag.field = header // tx_pizpalue_scroll_navigation_title
 *     section.dataWrap = c{field:uid}
 *     as = scrollnavigation
 * }
 */
class ScrollMenuProcessor implements DataProcessorInterface, SingletonInterface
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
        // Initialize availability
        $processedData['pizpalue']['menu']['scroll']['available'] = false;

        // Check configuration
        if ((bool)($processedData['pizpalue']['menu']['scroll']['enable'] ?? true) === false) {
            return $processedData;
        }

        // Get content elements
        if (($defaultPageUid = trim((string)($processedData['pizpalue']['menu']['scroll']['pageUid'] ?? ''))) === '') {
            $defaultPageUid = (string)$cObj->data['uid'];
        }
        $pageUid = $cObj->stdWrapValue('contentElementPid', $processorConfiguration, $defaultPageUid);
        $contentElements = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tt_content')
            ->select(
                ['*'],
                'tt_content',
                ['pid' => $pageUid, 'tx_pizpalue_scroll_navigation_enable' => 1],
                [],
                ['tx_pizpalue_scroll_navigation_position' => 'ASC', 'sorting' => 'ASC']
            )
            ->fetchAllAssociative();

        if ($contentElements === []) {
            return $processedData;
        }

        // Assign data and set status indicator
        if (count($scrollMenuItems = $this->getScrollMenuItems($cObj, $processorConfiguration, $contentElements)) > 0) {
            if (($defaultKey = trim($processedData['pizpalue']['menu']['scroll']['dataKey'] ?? '')) === '') {
                $defaultKey = 'scrollnavigation';
            }
            $key = $cObj->stdWrapValue('as', $processorConfiguration, $defaultKey);
            $processedData[$key] = $scrollMenuItems;
            $processedData['pizpalue']['menu']['scroll']['available'] = true;
        }
        return $processedData;
    }

    protected function getScrollMenuItems(
        ContentObjectRenderer $cObj,
        array $processorConfiguration,
        array $contentElements
    ): array {
        $scrollMenuItems = [];
        $localCObj = new ContentObjectRenderer($GLOBALS['TSFE']);
        foreach ($contentElements as $contentElement) {
            $scrollMenuItem = [];
            $localCObj->start($contentElement);
            if (($defaultTitle = $contentElement['tx_pizpalue_scroll_navigation_title']) === '') {
                $defaultTitle = $contentElement['header'];
            }
            if (($scrollMenuItem['title'] = $localCObj->stdWrapValue('title', $processorConfiguration, $defaultTitle)) === '') {
                continue;
            }
            if (($defaultTitleTag = $contentElement['header']) === '') {
                $defaultTitleTag = $contentElement['tx_pizpalue_scroll_navigation_title'];
            }
            $scrollMenuItem['titleTag'] = $localCObj->stdWrapValue('titleTag', $processorConfiguration, $defaultTitleTag);
            $scrollMenuItem['pageUid'] = $contentElement['pid'];
            $scrollMenuItem['section'] = $localCObj->stdWrapValue('section', $processorConfiguration, 'c' . $contentElement['uid']);
            $scrollMenuItem['data'] = $contentElement;
            $scrollMenuItem['link'] = $localCObj->getTypoLink_URL(
                sprintf('t3://page?uid=%d#%d', $contentElement['pid'], $contentElement['uid'])
            );
            $scrollMenuItems[] = $scrollMenuItem;
        }
        return $scrollMenuItems;
    }
}
