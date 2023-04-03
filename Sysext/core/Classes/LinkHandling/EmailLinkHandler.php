<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Sysext\Core\LinkHandling;

/**
 * Resolves emails
 * In addition to the core class typoscript constants can be used to define an email address.
 *
 * @see https://forge.typo3.org/issues/100380
 */
class EmailLinkHandler extends \TYPO3\CMS\Core\LinkHandling\EmailLinkHandler
{
    /**
     * Returns the email address without the "mailto:" prefix
     * in the 'email' property of the array.
     */
    public function resolveHandlerData(array $data): array
    {
        $linkParts = parse_url($data['email'] ?? '');
        $data['email'] = $linkParts['path'] ?? '';
        if ($data['email'] === '' && ($linkParts['fragment'] ?? '') !== '') {
            $data['email'] = '#' . $linkParts['fragment'];
        }
        if (isset($linkParts['query'])) {
            $result = [];
            parse_str($linkParts['query'], $result);
            foreach (['subject', 'cc', 'bcc', 'body'] as $additionalInfo) {
                if (isset($result[$additionalInfo])) {
                    $data[$additionalInfo] = $result[$additionalInfo];
                }
            }
        }
        return $data;
    }
}
