<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Format;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\ViewHelpers\Format\AbstractEncodingViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

class SchemaViewHelper extends AbstractEncodingViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $decodeOptions = self::getJsonOptions($arguments['decodeOptions']);
        $encodeOptions = self::getJsonOptions($arguments['encodeOptions']);
        $value = $renderChildrenClosure();
        if (!is_array($json = json_decode($value, true, 512, $decodeOptions))) {
            return 'Decoding the string to json didn\'t result in a array. Please check if \'decodeOptions\', '
                . '\'addSlashes\' and \'stripSlashes\' should be further specified.';
        }
        if ((bool)$arguments['removeEmptyElements']) {
            $json = ArrayUtility::removeArrayEntryByValue($json, '');
            $json = ArrayUtility::removeNullValuesRecursive($json);
        }
        return (string)json_encode($json, $encodeOptions);
    }

    private static function getJsonOptions(?string $optionsList): int
    {
        $result = 0;
        if (is_string($optionsList)) {
            $options = GeneralUtility::trimExplode(',', $optionsList);
            foreach ($options as $option) {
                if (is_int($value = constant($option))) {
                    $result |= $value;
                }
            }
        }
        return $result;
    }

    /**
     * Initialize ViewHelper arguments
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('value', 'string', 'String to format');
        $this->registerArgument(
            'removeEmptyElements',
            'bool',
            'If set removes empty elements',
            false,
            true
        );
        $this->registerArgument(
            'decodeOptions',
            'string',
            'Coma separated options for decoding the json string (see php manual for json_decode).',
            false
        );
        $this->registerArgument(
            'encodeOptions',
            'string',
            'Coma separated options for encoding the json array (see php manual for json_encode).',
            false
        );
    }
}
