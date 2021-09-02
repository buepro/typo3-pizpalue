<?php

/*
 * This file is part of the package buepro/pizpalue.
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

    /**
     *
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $decodeOptions = self::getJsonOptions($arguments['decodeOptions']);
        $encodeOptions = self::getJsonOptions($arguments['encodeOptions']);
        $value = $renderChildrenClosure();
        $json = json_decode($value, true, 512, $decodeOptions);
        if (!is_array($json ?? false)) {
            $message = 'Decoding the string to json didn\'t result in a array. Please check if \'decodeOptions\', '
                . '\'addSlashes\' and \'stripSlashes\' should be further specified.';
            return $message;
            //throw new \TYPO3\CMS\Core\Exception($message, 1596124988681);
        }
        if ($arguments['removeEmptyElements']) {
            $json = ArrayUtility::removeArrayEntryByValue($json, '');
            $json = ArrayUtility::removeNullValuesRecursive($json);
        }
        return json_encode($json, $encodeOptions);
    }

    /**
     * @param string $optionsList
     * @return int
     */
    private static function getJsonOptions($optionsList)
    {
        $result = 0;
        if ($optionsList) {
            $options = GeneralUtility::trimExplode(',', $optionsList);
            foreach ($options as $option) {
                $result |= constant($option);
            }
        }
        return (int) $result;
    }

    /**
     * Initialize ViewHelper arguments
     */
    public function initializeArguments()
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
