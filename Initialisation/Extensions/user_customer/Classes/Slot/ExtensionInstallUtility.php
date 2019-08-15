<?php


namespace Buepro\UserCustomer\Slot;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExtensionInstallUtility
{

    private function copyAdditionalConfiguration()
    {
        $source = Environment::getPublicPath()
            . '/typo3conf/ext/pizpalue/Resources/Private/FolderStructureTemplateFiles/AdditionalConfiguration.php';
        $destination = Environment::getPublicPath() . '/typo3conf/AdditionalConfiguration.php';
        if (!file_exists($source)) return false;
        if (!file_exists($destination)) {
            GeneralUtility::upload_copy_move($source, $destination);
        }
    }

    /**
     * Handles copying the default file AdditionalConfiguration.php
     *
     * @param $extensionKey
     */
    public function afterExtensionInstall($extensionKey)
    {
        if ($extensionKey !== 'user_customer') {
            return;
        }
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        if ($extensionConfiguration->get('user_customer', 'addAdditionalConfiguration')) {
            $this->copyAdditionalConfiguration();
        }
    }
}