<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Slot;

class ExtensionInstallUtility
{

    private function commentUserCustomerDependency()
    {
        if (class_exists(\TYPO3\CMS\Core\Core\Environment::class)) {
            $emconfFile = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/typo3conf/ext/pizpalue/ext_emconf.php';
        } else {
            // Fallback for TYPO3 V8
            // @extensionScannerIgnoreLine
            $emconfFile = PATH_site . 'typo3conf/ext/pizpalue/ext_emconf.php';
        }
        $content = file_get_contents($emconfFile);
        $commentToken = '// commented by install process';
        if (strstr($content, $commentToken) === false) {
            $content = str_replace("'user_customer'", $commentToken . " 'user_customer'", $content);
            file_put_contents($emconfFile, $content);
        }
    }

    static function copyDefaultSiteConfig()
    {
        // Just copy site configuration in case CMS version is 9
        if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < 9000000) return false;
        $sourceFile = \TYPO3\CMS\Core\Core\Environment::getPublicPath() .
            '/typo3conf/ext/pizpalue/Resources/Private/FolderStructureTemplateFiles/Sites_config.yaml';
        $siteDirectory = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/typo3conf/sites/';
        $targetDirectory = $siteDirectory . 'default/';
        // Just copy default configuration in case no site configuration exists yet
        if (!is_dir($siteDirectory)) {
            \TYPO3\CMS\Core\Utility\GeneralUtility::mkdir_deep($targetDirectory);
            \TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move($sourceFile, $targetDirectory . 'config.yaml');
            return true;
        }
        return false;
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
        $this->commentUserCustomerDependency();
        $this->copyDefaultSiteConfig();
    }
}
