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
        /** @var array{email: string} $data */
        [$data['email'], $queryPart] = [...explode('?', $data['email'], 2), null];
        if (stripos($data['email'], 'mailto:') === 0) {
            $data['email'] = substr($data['email'], 7);
        }
        if (isset($queryPart)) {
            $result = [];
            parse_str($queryPart, $result);
            foreach (['subject', 'cc', 'bcc', 'body'] as $additionalInfo) {
                if (isset($result[$additionalInfo])) {
                    $data[$additionalInfo] = $result[$additionalInfo];
                }
            }
        }
        return $data;
    }
}
