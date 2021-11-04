<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Cms\Core\LinkHandling;

use TYPO3\CMS\Core\LinkHandling\LinkHandlingInterface;

/**
 * Class PopoverLinkHandler
 *
 * Hint: Is used from BE when saving content element.
 *
 */
class PopoverLinkHandler implements LinkHandlingInterface
{
    /**
     * The Base URN for this link handling to act on
     * @var string
     */
    protected $baseUrn = 't3://pppopover';

    public function asString(array $parameters): string
    {
        $result = $this->baseUrn;
        unset($parameters['type']);
        $query = http_build_query($parameters);
        if ($query) {
            $result .= '?' . $query;
        }
        return $result;
    }

    public function resolveHandlerData(array $data): array
    {
        return $data;
    }
}
