<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Sysext\Frontend\TypoLink;

use TYPO3\CMS\Frontend\Event\AfterLinkIsGeneratedEvent;

final class PopoverLinkModifier
{
    public function __invoke(AfterLinkIsGeneratedEvent $event): void
    {
        $linkResult = $event->getLinkResult();
        if ($linkResult->getType() !== 'pppopover') {
            return;
        }
        $popoverAttributes = [
            'data-bs-html' => 'true',
            'data-bs-toggle' => 'popover',
            'data-bs-content' => $linkResult->getAttribute('popover-content'),
            'role' => 'button',
            'tabindex' => '0',
        ];
        $linkResult = $linkResult->withAttributes($popoverAttributes);
        $event->setLinkResult($linkResult);
    }
}
