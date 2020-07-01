<?php


namespace Buepro\Pizpalue\Core\LinkHandling;

use TYPO3\CMS\Core\LinkHandling\LinkHandlingInterface;

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
        if (preg_match('/###.+###/', $parameters['telephone'], $matches)) {
            $telephoneNumber = $matches[0];
        } else {
            $telephoneNumber = preg_replace('/(?:[^\d\+]+)/', '', $parameters['telephone']);
        }

        return 'tel:' . $telephoneNumber;
    }
}
