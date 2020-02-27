<?php

/*
 * This file is part of the package buepro/user_customer.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\UserCustomer\Slot;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExtensionInstallUtility
{
    /**
     * Copies the default site configuration delivered with the extension to the site configuration directory.
     * Just copies the default site configuration in case no site configuration exists.
     *
     * @return bool false if default site config couldn't be copied
     */
    private function copyDefaultSiteConfig()
    {
        $destination = Environment::getPublicPath() . '/typo3conf/sites/default';
        // Checks if a site configuration exists
        if (!glob(Environment::getPublicPath() . '/typo3conf/sites/*', GLOB_ONLYDIR)) {
            // Copies the default site configuration
            GeneralUtility::copyDirectory(
                'typo3conf/ext/user_customer/Resources/Private/FolderStructureTemplateFiles/sites',
                $destination
            );
            if (!file_exists($destination)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Copies the default AdditionalConfiguration file
     *
     * @return bool returns true if the default file has been copied
     */
    private function copyAdditionalConfiguration()
    {
        $source = Environment::getPublicPath()
            . '/typo3conf/ext/user_customer/Resources/Private/FolderStructureTemplateFiles/AdditionalConfiguration.php';
        $destination = Environment::getPublicPath() . '/typo3conf/AdditionalConfiguration.php';
        if (!file_exists($source)) {
            return false;
        }
        if (!file_exists($destination)) {
            return GeneralUtility::upload_copy_move($source, $destination);
        }
        return false;
    }

    /**
     * Handles copying the default file AdditionalConfiguration.php
     *
     * @param $extensionKey
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     */
    public function afterExtensionInstall($extensionKey)
    {
        if ($extensionKey !== 'user_customer') {
            return;
        }
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        if ($extensionConfiguration->get('user_customer', 'addSiteConfiguration')) {
            $this->copyDefaultSiteConfig();
        }
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        if ($extensionConfiguration->get('user_customer', 'addAdditionalConfiguration')) {
            $this->copyAdditionalConfiguration();
        }
    }
}
