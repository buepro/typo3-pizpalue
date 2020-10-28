<?php

declare(strict_types=1);

namespace Buepro\Pizpalue\Cms\Core\LinkHandling;

use TYPO3\CMS\Core\LinkHandling\LinkHandlingInterface;
use function GuzzleHttp\Psr7\build_query;

/**
 * Class PopoverLinkHandler
 *
 * Hint: Is used from BE when saving content element.
 *
 * @package Buepro\Pizpalue\Cms\Core\LinkHandling
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
