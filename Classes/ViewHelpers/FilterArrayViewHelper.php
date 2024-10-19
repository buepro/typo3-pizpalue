<?php

declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * This ViewHelper filters an array for that it just holds elements belonging to a key present in the
 * keylist. It is intended to be used with inline notation.
 *
 * Example:
 *
 * extensive = {0: {name: 'Luna', eyes: 'brown', points: 10}, 1: {name: 'Roman', eyes: 'green', points: 7}}
 * {pp:filterArray(source: extensive, keylist: 'name,points') -> f:variable(name: 'filtered')}
 *
 * filtered would now be {0: {name: 'Luna', points: 10}, 1: {name: 'Roman', points: 7}}
 *
 */
class FilterArrayViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('source', 'array', 'Array to be reduced');
        $this->registerArgument('keylist', 'string', 'Comma separated list from required keys', true);
    }

    /**
     * Filters the array.
     *
     * @return ?array
     */
    public function render()
    {
        $source = $this->arguments['source'] ?? null;
        $keylist = $this->arguments['keylist'] ?? '';
        if (!is_array($source)) {
            return null;
        }
        $keys = GeneralUtility::trimExplode(',', $keylist, true);
        $keys = array_flip($keys);
        $filtered = [];
        foreach ($source as $element) {
            if (is_array($element)) {
                $filtered[] = array_intersect_key($element, $keys);
            }
            if (is_object($element)) {
                $newElement = [];
                foreach ($keys as $key => $v) {
                    $callable = [$element, 'get' . ucfirst($key)];
                    if (is_callable($callable)) {
                        $newElement[$key] = call_user_func($callable);
                    }
                }
                $filtered[] = $newElement;
            }
        }
        return $filtered;
    }
}
