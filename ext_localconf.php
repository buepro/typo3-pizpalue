<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

/**
 * Bootstrap popover implementation
 */
(static function () {
    /** @extensionScannerIgnoreLine */
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['linkHandler']['pppopover'] =
        \Buepro\Pizpalue\Sysext\Core\LinkHandling\PopoverLinkHandler::class;
    $GLOBALS['TYPO3_CONF_VARS']['FE']['typolinkBuilder']['pppopover'] =
        \Buepro\Pizpalue\Sysext\Frontend\TypoLink\PopoverLinkBuilder::class;
})();

/**
 * Configure system extensions
 */
(static function () {
    /**
     * EXT:core
     * Register TelephoneLinkHandler and EmailLinkHandler
     * @extensionScannerIgnoreLine
     */
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['linkHandler']['telephone'] =
        \Buepro\Pizpalue\Sysext\Core\LinkHandling\TelephoneLinkHandler::class;
    /** @extensionScannerIgnoreLine */
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['linkHandler']['email'] =
        \Buepro\Pizpalue\Sysext\Core\LinkHandling\EmailLinkHandler::class;

    /**
     * EXT:form
     */
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('form')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(trim('
        module.tx_form {
            settings {
                yamlConfigurations {
                    200 = EXT:pizpalue/Configuration/Form/CustomFormSetup.yaml
                    260 = EXT:pizpalue/Configuration/Form/MailToSystem/BaseSetup.yaml
                    262 = EXT:pizpalue/Configuration/Form/MailToSystem/FormEditorSetup.yaml
                    264 = EXT:pizpalue/Configuration/Form/MailToSystem/FormEngineSetup.yaml
                }
            }
        }
        plugin.tx_form {
            settings {
                yamlConfigurations {
                    200 = EXT:pizpalue/Configuration/Form/CustomFormSetup.yaml
                    260 = EXT:pizpalue/Configuration/Form/MailToSystem/BaseSetup.yaml
                    264 = EXT:pizpalue/Configuration/Form/MailToSystem/FormEngineSetup.yaml
                }
            }
        }
    '));
    }
})();

/**
 * Configure 3rd party extensions
 */
(static function () {
    $pizpalueConfiguration = (\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
    ))->get('pizpalue');

    /**
     * EXT:container_elements
     */
    if (
        (bool)($pizpalueConfiguration['autoLoadStaticTSForExtensions'] ?? true) &&
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('container_elements') &&
        (($containerElementsVersion = \Buepro\Pizpalue\Utility\VersionUtility::getExtensionVersion('container_elements'))
            === 0 || $containerElementsVersion > 3001001)
    ) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
            '@import "EXT:pizpalue/Extensions/container_elements/Configuration/TypoScript/constants.typoscript"'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
            '@import "EXT:pizpalue/Extensions/container_elements/Configuration/TypoScript/setup.typoscript"'
        );
    }

    /**
     * EXT:easyconf
     */
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('easyconf')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
            "@import 'EXT:pizpalue/Extensions/easyconf/Configuration/TypoScript/constants.typoscript'"
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
            "@import 'EXT:pizpalue/Extensions/easyconf/Configuration/TypoScript/setup.typoscript'"
        );
    }
})();

/**
 * Various
 */
(static function () {
    /**
     * RTE: Add default configuration for pizpalue
     */
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['pizpalue'] = 'EXT:pizpalue/Configuration/RTE/Default.yaml';

    /**
     * Hook: DataHandler used to set image variants for content elements
     */
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']
    ['pizpalue'] = \Buepro\Pizpalue\Hook\DataHandlerHook::class;

    /**
     * Form engine user functions
     */
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals']
    ['Buepro\\Pizpalue\\UserFunction\\FormEngine\\CssEval'] = '';

    /**
     * Register "pp" as global fluid namespace
     */
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['pp'][] = 'Buepro\\Pizpalue\\ViewHelpers';
})();
