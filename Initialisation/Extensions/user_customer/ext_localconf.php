<?php

/*
 * This file is part of the package buepro/user_customer.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3_MODE') || die();

(function () {

    /**
     * After extension installation handler
     */
    $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
    $signalSlotDispatcher->connect(
        \TYPO3\CMS\Extensionmanager\Utility\InstallUtility::class,
        'afterExtensionInstall',
        \Buepro\UserCustomer\Slot\ExtensionInstallUtility::class,
        'afterExtensionInstall'
    );

    /**
     * Registers custom EXT:form configuration
     */
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('form')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(trim('
        module.tx_form {
            settings {
                yamlConfigurations {
                    300 = EXT:user_customer/Configuration/Form/CustomFormSetup.yaml
                }
            }
        }
        plugin.tx_form {
            settings {
                yamlConfigurations {
                    300 = EXT:user_customer/Configuration/Form/CustomFormSetup.yaml
                }
            }
        }
    '));
    }
})();
