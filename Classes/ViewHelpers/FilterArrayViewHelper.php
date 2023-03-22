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
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

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
    use CompileWithRenderStatic;

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
     */
    public function render(): ?array
    {
        /** @var array{source: ?array, keylist: string} $arguments */
        $arguments = $this->arguments;
        $source = $arguments['source'];
        $keylist = $arguments['keylist'];
        if ($source === null) {
            return $source;
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
                    $method = 'get' . ucfirst($key);
                    if (method_exists($element, $method)) {
                        $newElement[$key] = $element->$method(); // @phpstan-ignore-line
                    }
                }
                $filtered[] = $newElement;
            }
        }
        return $filtered;
    }
}
