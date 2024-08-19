<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\ReadService;

use Buepro\Pizpalue\Easyconf\EventHandler\PersistService\SocialNetworkService as SocialNetworkPersistService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\LinkHandling\TypoLinkCodecService;

class SocialNetworkService extends AbstractService
{
    public function process(): array
    {
        $this->handleSocialNetworks();
        return $this->formFields;
    }

    protected function handleSocialNetworks(): self
    {
        $linkCodecService = GeneralUtility::makeInstance(TypoLinkCodecService::class);
        foreach (SocialNetworkPersistService::$socialChannels as $channel) {
            $path = 'page.theme.socialmedia.channels.' . $channel . '.';
            $url = $this->typoScriptConstantMapper->getProperty($path . 'url');
            $label = $this->typoScriptConstantMapper->getProperty($path . 'label');
            $this->formFields['social_channel_' . $channel] = $linkCodecService->encode([
                'url' => $url,
                'title' => $label
            ]);
        }
        return $this;
    }
}
