<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler;

use Buepro\Easyconf\Event\AfterReadingPropertiesEvent;
use Buepro\Pizpalue\Easyconf\EventHandler\ReadService\AppIconService;
use Buepro\Pizpalue\Easyconf\EventHandler\ReadService\ColorService;
use Buepro\Pizpalue\Easyconf\EventHandler\ReadService\FontService;
use Buepro\Pizpalue\Easyconf\EventHandler\ReadService\MenuService;
use Buepro\Pizpalue\Easyconf\EventHandler\ReadService\ScssService;
use Buepro\Pizpalue\Easyconf\EventHandler\ReadService\SocialNetworkService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AfterReadingPropertiesEventHandler
{
    /** @var array */
    protected $formFields = [];

    public function __invoke(AfterReadingPropertiesEvent $event): void
    {
        $formFields = $event->getFormFields();
        $classes = [AppIconService::class, ColorService::class, FontService::class, MenuService::class,
            ScssService::class, SocialNetworkService::class];
        foreach ($classes as $class) {
            $formFields = GeneralUtility::makeInstance($class, $formFields)->process();
        }
        $event->setFormFields($formFields);
    }
}
