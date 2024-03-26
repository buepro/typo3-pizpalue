<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\News\EventHandler;

use TYPO3\CMS\Core\Configuration\Event\AfterFlexFormDataStructureParsedEvent;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ModifyFlexformEvent
{
    public function __invoke(AfterFlexFormDataStructureParsedEvent $event): void
    {
        $dataStructure = $event->getDataStructure();
        $identifier = $event->getIdentifier();

        if (
            $identifier['type'] === 'tca' && $identifier['tableName'] === 'tt_content'
            && ($identifier['dataStructureKey'] === '*,news_pi1' || $identifier['dataStructureKey'] === '*,news_newsliststicky')
        ) {
            $flexformConfigFile = GeneralUtility::getFileAbsFileName('EXT:pizpalue/Extensions/news/Configuration/FlexForms/Advanced.xml');
            $flexformConfig = file_get_contents($flexformConfigFile);

            if ($flexformConfig) {
                $extraDataStructure['sheets']['ppAdvanced'] = GeneralUtility::xml2array($flexformConfig);
                ArrayUtility::mergeRecursiveWithOverrule($dataStructure, $extraDataStructure);
            }
        }

        $event->setDataStructure($dataStructure);
    }
}
