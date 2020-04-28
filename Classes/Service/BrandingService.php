<?php
declare(strict_types = 1);

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Service;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * BrandingService
 */
class BrandingService
{
    /**
     * @var string
     */
    const EXT_KEY = 'pizpalue';

    /**
     * @param string $extension
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     */
    public function setBackendStyling($extension = null)
    {
        if ($extension === self::EXT_KEY && class_exists(ExtensionConfiguration::class)) {
            $extensionConfiguration = GeneralUtility::makeInstance(
                ExtensionConfiguration::class
            );
            $backendConfiguration = $extensionConfiguration->get('backend');

            if (!isset($backendConfiguration['loginLogo']) || empty(trim($backendConfiguration['loginLogo'])) ||
                strstr($backendConfiguration['loginLogo'], 'bootstrap_package')) {
                $backendConfiguration['loginLogo'] = 'EXT:pizpalue/Resources/Public/Images/backend-login-logo.png';
            }
            if (!isset($backendConfiguration['loginBackgroundImage']) || empty(trim($backendConfiguration['loginBackgroundImage'])) ||
                strstr($backendConfiguration['loginBackgroundImage'], 'bootstrap_package')) {
                $backendConfiguration['loginBackgroundImage'] = 'EXT:pizpalue/Resources/Public/Images/backend-login-background.jpg';
            }
            if (!isset($backendConfiguration['backendLogo']) || empty(trim($backendConfiguration['backendLogo'])) ||
                strstr($backendConfiguration['backendLogo'], 'bootstrap_package')) {
                $backendConfiguration['backendLogo'] = 'EXT:pizpalue/Resources/Public/Images/backend-logo.svg';
            }

            $extensionConfiguration->set('backend', '', $backendConfiguration);
        }
    }
}
