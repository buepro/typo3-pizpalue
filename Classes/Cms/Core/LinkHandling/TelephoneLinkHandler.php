<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Cms\Core\LinkHandling;

/**
 * Resolves telephone numbers.
 * In addition to the core class it allows to use `TS setup constants`.
 *
 * Note:
 */
class TelephoneLinkHandler extends \TYPO3\CMS\Core\LinkHandling\TelephoneLinkHandler
{

    /**
     * Returns the link to a telephone number as a string
     *
     * @param array $parameters
     * @return string
     */
    public function asString(array $parameters): string
    {
        if ((int)preg_match('/###.+###/', $parameters['telephone'], $matches) > 0) {
            $telephoneNumber = $matches[0];
        } else {
            $telephoneNumber = preg_replace('/(?:[^\d\+]+)/', '', $parameters['telephone']);
        }

        return 'tel:' . $telephoneNumber;
    }
}
