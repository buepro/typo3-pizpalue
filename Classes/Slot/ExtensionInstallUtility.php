<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Slot;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extensionmanager\Utility\InstallUtility;

class ExtensionInstallUtility
{

    /**
     * Copies the site configuration delivered with the extension to the site configuration directory.
     */
    public static function copyDefaultSiteConfig()
    {
        $destination = Environment::getPublicPath() . '/typo3conf/sites/pizpalue';
        if (!file_exists($destination)) {
            GeneralUtility::copyDirectory(
                'typo3conf/ext/pizpalue/Resources/Private/FolderStructureTemplateFiles/sites',
                $destination
            );
        }
    }

    /**
     * Installs the extension user_customer. In case it isn't available under typo3conf/ext it will be copied from
     * the folder EXT/pizpalue/Initialisation/Extensions/
     *
     * @return bool true if extension user_customer could be installed
     */
    private function installCustomerExtension()
    {
        $source = Environment::getPublicPath() . '/typo3conf/ext/pizpalue/Initialisation/Extensions/user_customer';
        $destination = Environment::getPublicPath() . '/typo3conf/ext/user_customer';
        if (!file_exists($source)) {
            return false;
        }
        if (!file_exists($destination)) {
            GeneralUtility::copyDirectory($source, $destination);
            if (!file_exists($destination)) {
                return false;
            }
        }
        $installUtility = GeneralUtility::makeInstance(InstallUtility::class);
        if (!$installUtility->isLoaded('user_customer')) {
            $installUtility->reloadAvailableExtensions();
            $installUtility->install('user_customer');
        }
        return $installUtility->isLoaded('user_customer');
    }

    /**
     * Copies the translation files for the bootstrap_package to the typo3conf/l10n directory.
     *
     * @return bool false if translation files couldn't be copied
     */
    private function copyBootstrapPackageTranslations()
    {
        $source = Environment::getPublicPath() . '/typo3conf/ext/pizpalue/Resources/Private/'
            . 'FolderStructureTemplateFiles/l10n/fi/bootstrap_package';
        $destination = Environment::getPublicPath() . '/typo3conf/l10n/fi/bootstrap_package';
        if (!file_exists($destination)) {
            GeneralUtility::copyDirectory($source, $destination);
            if (!file_exists($destination)) {
                return false;
            }
        }
    }

    /**
     * Handles the installation from the extension user_customer as well as the copying from a default
     * site configuration.
     *
     * @param $extensionKey
     */
    public function afterExtensionInstall($extensionKey)
    {
        if ($extensionKey !== 'pizpalue') {
            return;
        }
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        if ($extensionConfiguration->get('pizpalue', 'installCustomerExtension')) {
            $this->installCustomerExtension();
        }
        if ($extensionConfiguration->get('pizpalue', 'addSiteConfiguration')) {
            $this->copyDefaultSiteConfig();
        }
        $this->copyBootstrapPackageTranslations();
    }
}
