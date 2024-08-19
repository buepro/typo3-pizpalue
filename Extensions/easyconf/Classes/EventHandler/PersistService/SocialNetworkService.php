<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\PersistService;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\LinkHandling\TypoLinkCodecService;

class SocialNetworkService extends AbstractService
{
    /** @var string[]  */
    public static $socialChannels = ['facebook', 'github', 'instagram', 'linkedin', 'mastodon', 'pinterest',
        'rss', 'twitter', 'vimeo', 'vk', 'xing', 'youtube'];

    public function process(): void
    {
        $typoLinkCodecService = GeneralUtility::makeInstance(TypoLinkCodecService::class);
        foreach (self::$socialChannels as $channel) {
            $path = 'page.theme.socialmedia.channels.' . $channel . '.';
            $link = $this->formFields['social_channel_' . $channel] ?? '';
            $decoded = $typoLinkCodecService->decode($link);
            if ($this->typoScriptMapper->getInheritedProperty($path . 'label') !== $decoded['title']) {
                $this->typoScriptMapper->bufferProperty(
                    $path . 'label',
                    $decoded['title'] ?? ''
                );
            }
            if ($this->typoScriptMapper->getInheritedProperty($path . 'url') !== $decoded['url']) {
                $this->typoScriptMapper->bufferProperty(
                    $path . 'url',
                    $decoded['url'] ?? ''
                );
            }
        }
    }
}
