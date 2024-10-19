<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\DataProcessing;

use Buepro\Pizpalue\Service\FrameDataService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class ContentElementDataProcessor implements DataProcessorInterface
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
        $processedData['ppData'] = (new FrameDataService)->getData($processedData['data'] ?? [], $processedData['pizpalue'] ?? []);
        $processedData['frameAttributes'] = $this->addAttributes(
            $processedData['frameAttributes'],
            $processedData['ppData']
        );
        $processedData['ppData']['framelessAttributes'] = GeneralUtility::implodeAttributes($this->addAttributes(
            $processedData['ppFramelessAttributes'],
            $processedData['ppData']
        ), true);
        return $processedData;
    }

    private function addAttributes(array $baseAttributes, array $ppData): array
    {
        if (($ppClasses = $ppData['classes'] ?? []) !== []) {
            $baseAttributes['class'] = trim(implode(' ', [
                $baseAttributes['class'] ?? '',
                ...$ppClasses,
            ]));
        }
        if (($ppStyles = $ppData['styles'] ?? []) !== []) {
            $baseAttributes['style'] = trim(implode('; ', [
                $baseAttributes['style'] ?? '',
                ...$ppStyles,
            ]), " \t\n\r\0\x0B;") . ';';
        }
        if (($ppAttributes = $ppData['attributes'] ?? []) !== []) {
            foreach ($ppAttributes as $attribute) {
                [$name, $value] = explode('="', $attribute, 2);
                $baseAttributes[$name] = trim($value, " \t\n\r\0\x0B\"");
            }
        }
        return $baseAttributes;
    }
}
