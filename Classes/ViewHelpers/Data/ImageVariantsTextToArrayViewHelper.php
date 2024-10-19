<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Data;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class ImageVariantsTextToArrayViewHelper
 *
 * Converts an input string to an array.
 *
 * The input text has the form: "[default: 0.3,] xxl: 1.0, xl: 1.3, ..." where the output array
 * contains the keys used by the image variants.
 *
 * The items from the input string are coma separated. The resulting array elements are of type float and are computed
 * as following:
 *
 * - When the input text is empty the default value will be assigned to all elements. The default value is obtained
 *   by the default value from the view helper and can be overwritten by placing a default definition ( e.g.
 *   `default: 0.5`) in the text field. If both default assignments are missing 0 will be used.
 * - In case just one item is defined, it will be used for all elements.
 * - In all other cases the values from each item will be assigned to the corresponding element.
 *
 */
class ImageVariantsTextToArrayViewHelper extends AbstractViewHelper
{
    /**
     * @var string[]
     */
    protected static $breakpointMap = [
        'xxl' => 'default',
        'xl' => 'xlarge',
        'lg' => 'large',
        'md' => 'medium',
        'sm' => 'small',
        'xs' => 'extrasmall',
    ];

    /**
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('as', 'string', 'Name from the resulting array', false);
        $this->registerArgument('text', 'string', 'Comma separated list from screen breakpoint values.', true);
        $this->registerArgument('default', 'float', 'Default value assigned to array elements.', false);
    }

    /**
     * @return mixed
     */
    public function render()
    {
        $text = $this->arguments['text'] ?? '';
        $default = (float)$this->arguments['default'];
        if (preg_match_all('/default\s*:\s([\d\.]+)/', $text, $matches) === 1) {
            $default = (float)$matches[1][0];
        }
        $result = array_fill_keys(array_values(self::$breakpointMap), $default);
        $lines = GeneralUtility::trimExplode(',', $text, true);
        foreach ($lines as $line) {
            $parts = GeneralUtility::trimExplode(':', $line, true);
            if (count($parts) === 2 && array_key_exists($parts[0], self::$breakpointMap)) {
                $result[self::$breakpointMap[$parts[0]]] = (float) $parts[1];
            }
        }
        if (isset($this->arguments['as']) && $this->arguments['as'] !== '') {
            $this->renderingContext->getVariableProvider()->add($this->arguments['as'], $result);
            return '';
        }
        return $result;
    }
}
