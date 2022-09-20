<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler;

use Buepro\Easyconf\Event\BeforePersistingPropertiesEvent;
use Buepro\Easyconf\Mapper\TypoScriptConstantMapper;
use Buepro\Easyconf\Utility\TcaUtility;
use Buepro\Pizpalue\Easyconf\EventHandler\PersistService\AppiconService;
use Buepro\Pizpalue\Easyconf\EventHandler\PersistService\ColorService;
use Buepro\Pizpalue\Easyconf\EventHandler\PersistService\CookieService;
use Buepro\Pizpalue\Easyconf\EventHandler\PersistService\FontService;
use Buepro\Pizpalue\Easyconf\EventHandler\PersistService\LogoService;
use Buepro\Pizpalue\Easyconf\EventHandler\PersistService\MenuService;
use Buepro\Pizpalue\Easyconf\EventHandler\PersistService\NewsEnhancerService;
use Buepro\Pizpalue\Easyconf\EventHandler\PersistService\PageNotFoundService;
use Buepro\Pizpalue\Easyconf\EventHandler\PersistService\ScssService;
use Buepro\Pizpalue\Easyconf\EventHandler\PersistService\SocialNetworkService;
use Buepro\Pizpalue\Easyconf\EventHandler\PersistService\UrlService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BeforePersistingPropertiesEventHandler
{
    public function __invoke(BeforePersistingPropertiesEvent $event): void
    {
        $serviceClassNames = [AppiconService::class, ColorService::class, CookieService::class, FontService::class,
            LogoService::class, MenuService::class, NewsEnhancerService::class, PageNotFoundService::class,
            ScssService::class, SocialNetworkService::class, UrlService::class];
        $formFields = $event->getFormFields();
        $configurationRecord = $event->getConfigurationRecord();
        foreach ($serviceClassNames as $serviceClassName) {
            GeneralUtility::makeInstance(
                $serviceClassName,
                $formFields,
                $configurationRecord
            )->process();
        }
        foreach ($formFields as $field => $value) {
            if (
                $value === '??' &&
                ($class = TcaUtility::getMappingClass($field)) !== null &&
                $class = TypoScriptConstantMapper::class
            ) {
                GeneralUtility::makeInstance($class)->removePropertyFromBuffer(TcaUtility::getMappingPath($field));
            }
        }
    }
}
