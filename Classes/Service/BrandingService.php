<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Service;

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Package\Event\AfterPackageActivationEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extensionmanager\Event\AfterExtensionFilesHaveBeenImportedEvent;

/**
 * BrandingService
 */
class BrandingService
{
    /**
     * @var string
     */
    protected const EXT_KEY = 'pizpalue';

    /**
     * @param AfterExtensionFilesHaveBeenImportedEvent | AfterPackageActivationEvent $event
     * @return void
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public function __invoke($event): void
    {
        $this->setBackendStyling($event->getPackageKey());
    }

    /**
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public function setBackendStyling(?string $extension = null): void
    {
        if ($extension === self::EXT_KEY && class_exists(ExtensionConfiguration::class)) {
            $extensionConfiguration = GeneralUtility::makeInstance(
                ExtensionConfiguration::class
            );
            $backendConfiguration = $extensionConfiguration->get('backend');
            if (!is_array($backendConfiguration)) {
                return;
            }

            if (
                !isset($backendConfiguration['loginLogo']) || trim($backendConfiguration['loginLogo']) === '' ||
                strpos($backendConfiguration['loginLogo'], 'bootstrap_package') !== false
            ) {
                $backendConfiguration['loginLogo'] = 'EXT:pizpalue/Resources/Public/Images/backend-login-logo.svg';
            }
            if (
                !isset($backendConfiguration['loginBackgroundImage']) ||
                trim($backendConfiguration['loginBackgroundImage']) === '' ||
                strpos($backendConfiguration['loginBackgroundImage'], 'bootstrap_package') !== false
            ) {
                $backendConfiguration['loginBackgroundImage'] = 'EXT:pizpalue/Resources/Public/Images/backend-login-background.jpg';
            }
            if (
                !isset($backendConfiguration['backendLogo']) || trim($backendConfiguration['backendLogo']) === '' ||
                strpos($backendConfiguration['backendLogo'], 'bootstrap_package') !== false
            ) {
                $backendConfiguration['backendLogo'] = 'EXT:pizpalue/Resources/Public/Images/backend-logo.svg';
            }

            $extensionConfiguration->set('backend', $backendConfiguration);
        }
    }
}
