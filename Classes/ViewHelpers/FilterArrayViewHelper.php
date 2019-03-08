<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 07/03/2019
 * Time: 19:48
 */

namespace Buepro\Pizpalue\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This ViewHelper filters an array for that it just holds elements belonging to a key present in the
 * keylist. It is intended to be used with inline notation.
 *
 * Example:
 *
 * extensive = {0: {name: 'Luna', eyes: 'brown', points: 10}, 1: {name: 'Roman', eyes: 'green', points: 7}}
 * {pp:reduceArray(source: extensive, keylist: 'name,points') -> f:variable(name: 'filtered')}
 *
 * filtered would now be {0: {name: 'Luna', points: 10}, 1: {name: 'Roman', points: 7}}
 *
 * @package Buepro\Pizpalue\ViewHelpers
 */
class FilterArrayViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @return void
     */
    public function initializeArguments() {
        $this->registerArgument('source', 'array', 'Array to be reduced');
        $this->registerArgument('keylist', 'string', 'Comma separated list from required keys', TRUE);
    }

    /**
     * Filters the array.
     *
     * @return array
     */
    public function render()
    {
        $source = $this->arguments['source'];
        $keylist = $this->arguments['keylist'];
        if ($source === NULL) {
            return $source;
        }
        $keys = GeneralUtility::trimExplode(',',$keylist,true);
        $keys = array_flip($keys);
        $filtered = [];
        foreach($source as $element) {
            if (is_array($element)) {
                $filtered[] = array_intersect_key($element,$keys);
            }
            if (is_object($element)) {
                $newElement = [];
                foreach ($keys as $key => $v) {
                    $method = 'get' . ucfirst($key);
                    if (method_exists($element,$method)) {
                        $newElement[$key] = call_user_func([$element, $method]);
                    }
                }
                $filtered[] = $newElement;
            }
        }
        return $filtered;
    }
}