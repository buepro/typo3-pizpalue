<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\DataProcessing;

use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Add properties to menu items to better support the active state from shortcuts and alter the active property.
 *
 * The following integer properties are added:
 *
 * - coreActive: The original value the core menu processor assigned to the property `active`
 * - isInRootline: Flag indicating if the item belongs to the root line
 * - isShortcut: Flag indicating if the item is a shortcut
 * - shortcutTargetIsCurrent: Flag indication if the shortcut target page is the current page
 * - ppActive: The new active state (as well assigned to the property `active`)
 *
 * To use the original active state as defined by the core menu processor the following TS configuration can be used:
 *
 * 10 = Buepro\Pizpalue\DataProcessing\PostMenuProcessor
 * 10 {
 *     activeProperty = coreActive
 * }
 */
class PostMenuProcessor implements DataProcessorInterface, SingletonInterface
{
    /** @var ?array $rootLine Keys correspond to page uid, value to page data */
    protected $rootLine = null;

    /** @var ?int $pageId */
    protected $pageId;

    public function __construct()
    {
        if (is_array($this->rootLine)) {
            return;
        }
        $this->rootLine = [];
        foreach ($GLOBALS['TSFE']->rootLine as $data) {
            $this->rootLine[$data['uid']] = $data;
        }
    }

    /**
     * @inheritDoc
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $processedData['coreActive'] = (int)$processedData['active'];
        $processedData['isInRootLine'] = (int)isset($this->rootLine[$processedData['data']['uid']]);
        $processedData['isShortcut'] = (int)($processedData['data']['doktype'] === PageRepository::DOKTYPE_SHORTCUT);
        $processedData['shortcutTargetIsCurrent'] = 0;
        $this->pageId = $this->pageId ?? $GLOBALS['TYPO3_REQUEST']->getAttribute('routing')->getPageId();
        if (
            (int)$processedData['data']['doktype'] === PageRepository::DOKTYPE_SHORTCUT &&
            (int)$processedData['data']['shortcut'] === $this->pageId
        ) {
            $processedData['shortcutTargetIsCurrent'] = 1;
        }
        $processedData['ppActive'] = (int)((bool)$processedData['isInRootLine'] || (bool)$processedData['shortcutTargetIsCurrent']);
        $activeProperty = $processorConfiguration['activeProperty'] ?? 'ppActive';
        $processedData['active'] = (int)($processedData[$activeProperty] ?? $processedData['ppActive']);
        return $processedData;
    }
}
