<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Data;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class VariantsTextToArrayViewHelper
 *
 * Converts an input string to an array.
 *
 * The input text has the form: "xl: 1.0, lg: 1.3, ..." where the output array
 * contains the keys used by the image variants.
 *
 * The items from the input string are coma separated. The resulting array elements are of type float and are computed
 * as following:
 *
 * - When the input text is empty the default value will be assigned.
 * - In case just one item is defined, it will be used for all elements.
 * - In all other cases the values from each item will be assigned to the corresponding element.
 *
 */
class ImageVariantsTextToArrayViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    protected static $breakpointMap = [
        'xl' => 'default',
        'lg' => 'large',
        'md' => 'medium',
        'sm' => 'small',
        'xs' => 'extrasmall'
    ];

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('as', 'string', 'Name from the resulting array', true);
        $this->registerArgument('text', 'string', 'Comma separated list from screen breakpoint values.', true);
        $this->registerArgument('default', 'float', 'Default value assigned to array elements.', false);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $result = array_fill_keys(array_values(self::$breakpointMap), (float) $arguments['default']);
        $lines = GeneralUtility::trimExplode(',', $arguments['text'], true);
        if (count($lines) === 1) {
            // We assume a single defined value should be used for all breakpoints
            $result = array_fill_keys(array_values(self::$breakpointMap), (float) $lines[0]);
        }
        foreach ($lines as $line) {
            $parts = GeneralUtility::trimExplode(':', $line, true);
            if (count($parts) === 2 && array_key_exists($parts[0], self::$breakpointMap)) {
                $result[self::$breakpointMap[$parts[0]]] = (float) $parts[1];
            }
        }
        $renderingContext->getVariableProvider()->add($arguments['as'], $result);
    }
}
