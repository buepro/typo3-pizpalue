<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

/**
 * PageTS
 */
(static function () {
    // Initialization
    $extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
    );
    $bootstrapPackageConfiguration = $extensionConfiguration->get('bootstrap_package');
    $pizpalueConfiguration = $extensionConfiguration->get('pizpalue');

    // Add BackendLayouts for the BackendLayout DataProvider
    if (!(bool) $bootstrapPackageConfiguration['disablePageTsBackendLayouts']) {
        // Disable some bootstrap_package backend layouts
        if (!(bool) $pizpalueConfiguration['enableBootstrapPackageBackendLayouts']) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
                "@import 'EXT:pizpalue/Configuration/TsConfig/Page/Mod/WebLayout/DisableBackendLayouts.tsconfig'"
            );
        }
        // Pizpalue backend layouts
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            "@import 'EXT:pizpalue/Configuration/TsConfig/Page/Mod/WebLayout/BackendLayouts/*.tsconfig'"
        );
    }

    // Remove bootstrap package container elements
    if (!(bool) $pizpalueConfiguration['enableBootstrapPackageContainerElements'] &&
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('container_elements')
    ) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            "@import 'EXT:pizpalue/Configuration/TsConfig/Page/ContentElement/RemoveBootstrapPackageContainerElements.tsconfig'"
        );
    }

    // Default PageTS for TCEFORM
    if ((bool) $pizpalueConfiguration['enableDefaultPageTSconfig']) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            "@import 'EXT:pizpalue/Configuration/TsConfig/Page/TCEFORM.tsconfig'"
        );
    }
})();

/**
 * Additional content elements
 */
(static function () {
    // Initialization
    $pizpalueConfiguration = (\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
    ))->get('pizpalue');

    // Add page tsconfig
    if ((bool) $pizpalueConfiguration['enableDefaultPageTSconfig']) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            "@import 'EXT:pizpalue/Configuration/TsConfig/Page/ContentElement/All.tsconfig'"
        );
    }
})();

/**
 * Bootstrap popover implementation
 */
(static function () {
    /** @extensionScannerIgnoreLine */
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['linkHandler']['pppopover'] =
        \Buepro\Pizpalue\Sysext\Core\LinkHandling\PopoverLinkHandler::class;
    $GLOBALS['TYPO3_CONF_VARS']['FE']['typolinkBuilder']['pppopover'] =
        \Buepro\Pizpalue\Sysext\Frontend\TypoLink\PopoverLinkBuilder::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typoLink_PostProc']['pppopover'] =
        \Buepro\Pizpalue\Sysext\Frontend\Hook\PopoverTypolinkHook::class . '->postProcess';
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
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['linkHandler']['email'] =
        \Buepro\Pizpalue\Sysext\Core\LinkHandling\EmailLinkHandler::class;

    /**
     * EXT:backend
     */
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('backend')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
            '@import "EXT:pizpalue/Sysext/backend/Configuration/TypoScript/setup.typoscript"'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '@import "EXT:pizpalue/Sysext/backend/Configuration/TsConfig/page.tsconfig"'
        );
    }

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
     * EXT:bootstrap_package
     *
     * Load TS for bootstrap_package 12.0 compatibility.
     *
     * @todo Might be removed once the bootstrap package provides it (v12.1?)
     */
    $bootstrapPackageVersion = \Buepro\Pizpalue\Utility\VersionUtility::getExtensionVersion('bootstrap_package');
    if ($bootstrapPackageVersion >= 12000000 && $bootstrapPackageVersion < 12001000) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
            '@import "EXT:pizpalue/Extensions/bootstrap_package/Compatibility120/Configuration/TypoScript/setup.typoscript"'
        );
    }

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
     * EXT:news
     */
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('news')) {
        if ((bool)$pizpalueConfiguration['enableDefaultPageTSconfig']) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
                "@import 'EXT:pizpalue/Extensions/news/Configuration/TsConfig/Page.tsconfig'"
            );
        }
    }

    /**
     * EXT:eventnews
     */
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('eventnews')) {
        if ((bool)$pizpalueConfiguration['enableDefaultPageTSconfig']) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
                "@import 'EXT:pizpalue/Extensions/eventnews/Configuration/TsConfig/Page.tsconfig'"
            );
        }
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
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('RTE.default.preset = pizpalue');

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
     * Upgrade wizards
     */
    $upgradeSteps = ['ContentElementXxl', 'ContentElementBootstrapClasses', 'ContentElementAttributes',
        'EmphasizeMedia', 'ContentElementPizpalueClasses', 'ContentElementHeadingClasses'];
    foreach ($upgradeSteps as $upgradeStep) {
        $className = 'Buepro\\Pizpalue\\Updates\\' . $upgradeStep . 'Update';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][$className] = $className;
    }

    /**
     * Register "pp" as global fluid namespace
     */
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['pp'][] = 'Buepro\\Pizpalue\\ViewHelpers';
})();
