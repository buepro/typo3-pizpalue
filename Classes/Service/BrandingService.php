<?php

/**
 * Inspired by bootstrap_package from Benjamin Kott
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
     */
    public function setBackendStyling($extension = null)
    {
        if ($extension === self::EXT_KEY && class_exists(ExtensionConfiguration::class)) {
            $extensionConfiguration = GeneralUtility::makeInstance(
                ExtensionConfiguration::class
            );
            $backendConfiguration = $extensionConfiguration->get('backend');

            if (!isset($backendConfiguration['loginLogo']) || empty(trim($backendConfiguration['loginLogo'])) ||
                strstr($backendConfiguration['loginLogo'],'bootstrap_package')) {
                $backendConfiguration['loginLogo'] = 'EXT:pizpalue/Resources/Public/Images/backend-login-logo.png';
            }
            if (!isset($backendConfiguration['loginBackgroundImage']) || empty(trim($backendConfiguration['loginBackgroundImage'])) ||
                strstr($backendConfiguration['loginBackgroundImage'],'bootstrap_package')) {
                $backendConfiguration['loginBackgroundImage'] = 'EXT:pizpalue/Resources/Public/Images/backend-login-background.jpg';
            }
            if (!isset($backendConfiguration['backendLogo']) || empty(trim($backendConfiguration['backendLogo'])) ||
                strstr($backendConfiguration['backendLogo'],'bootstrap_package')) {
                $backendConfiguration['backendLogo'] = 'EXT:pizpalue/Resources/Public/Images/backend-logo.svg';
            }

            $extensionConfiguration->set('backend', '', $backendConfiguration);
        }
    }
}
