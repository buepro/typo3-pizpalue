<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Slot;

use TYPO3\CMS\Core\Core\Environment,
    TYPO3\CMS\Core\Utility\GeneralUtility,
    TYPO3\CMS\Extensionmanager\Utility\InstallUtility,
    TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

class ExtensionInstallUtility
{

    public static function copyDefaultSiteConfig()
    {
        // Just copy site configuration in case CMS version is 9
        if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < 9000000) {
            return false;
        }
        $sourceFile = Environment::getPublicPath() .
            '/typo3conf/ext/pizpalue/Resources/Private/FolderStructureTemplateFiles/Sites_config.yaml';
        $siteDirectory = Environment::getPublicPath() . '/typo3conf/sites/';
        $targetDirectory = $siteDirectory . 'default/';
        // Just copy default configuration in case no site configuration exists yet
        if (!is_dir($siteDirectory)) {
            GeneralUtility::mkdir_deep($targetDirectory);
            GeneralUtility::upload_copy_move($sourceFile, $targetDirectory . 'config.yaml');
            return true;
        }
        return false;
    }

    /**
     * Installs the extension user_customer. In case it isn't available under typo3conf/ext it will be copied from
     * the folder EXT/pizpalue/Initialisation/Extensions/
     */
    private function installCustomerExtension() {
        $destination = Environment::getPublicPath() . '/typo3conf/ext/user_customer';
        if (!file_exists($destination)) {
            GeneralUtility::copyDirectory(
                'typo3conf/ext/pizpalue/Initialisation/Extensions/user_customer',
                $destination
            );
        }
        $installUtility = GeneralUtility::makeInstance(
            InstallUtility::class
        );
        $installUtility->install('user_customer');
    }

    /**
     * Comment the dependency to extension user_customer.
     * The object is just to install user_customer once on the system.
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
            $extensionConfiguration->set('pizpalue', 'installCustomerExtension', 0);
        }
        $this->copyDefaultSiteConfig();
    }
}
