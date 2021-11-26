<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

(function () {
    /**
     * Make extension configurations accessible
     */
    if (1) {
        $extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
        );
        $bootstrapPackageConfiguration = $extensionConfiguration->get('bootstrap_package');
        $pizpalueConfiguration = $extensionConfiguration->get('pizpalue');
    }

    /**
     * Register icons
     */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'systeminformation-pizpalue',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:pizpalue/Resources/Public/Icons/SystemInformation/pizpalue.svg']
    );

    /**
     * TS: Register custom EXT:form configurations
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

    /**
     * PageTS
     */
    if (1) {
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
        // Default PageTS for TCEMAIN, TCEFORM
        if ((bool) $pizpalueConfiguration['enableDefaultPageTSconfig']) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
                "@import 'EXT:pizpalue/Configuration/TsConfig/Page/TCEMAIN.tsconfig'"
            );
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
                "@import 'EXT:pizpalue/Configuration/TsConfig/Page/TCEFORM.tsconfig'"
            );
        }
    }

    /**
     * RTE
     */
    if (1) {
        /**
         * Add default RTE configuration for pizpalue
         */
        $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['pizpalue'] = 'EXT:pizpalue/Configuration/RTE/Default.yaml';
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('RTE.default.preset = pizpalue');

        /**
         * Register TelephoneLinkHandler
         */
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['linkHandler']['telephone'] = \Buepro\Pizpalue\Cms\Core\LinkHandling\TelephoneLinkHandler::class;

        /**
         * Register PopoverLinkHandler, PopoverLinkBuilder, PopoverLinkHook
         */
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['linkHandler']['pppopover'] = \Buepro\Pizpalue\Cms\Core\LinkHandling\PopoverLinkHandler::class;
        $GLOBALS['TYPO3_CONF_VARS']['FE']['typolinkBuilder']['pppopover'] = \Buepro\Pizpalue\Cms\Frontend\Typolink\PopoverLinkBuilder::class;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typoLink_PostProc']['pppopover'] = \Buepro\Pizpalue\Hook\PopoverTypolinkHook::class . '->postProcess';
    }

    /**
     * Register "pp" as global fluid namespace
     */
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['pp'][] = 'Buepro\\Pizpalue\\ViewHelpers';

    /**
     * Additional content elements
     */
    if (1) {
        /**
         * Register icons
         */
        $icons = ['modal-dialog', 'list-categorized-content', 'schema', 'picoverlay', 'emphasize-media'];
        foreach ($icons as $icon) {
            $iconRegistry->registerIcon(
                'content-pizpalue-' . $icon,
                \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
                ['source' => 'EXT:pizpalue/Resources/Public/Icons/ContentElements/' . $icon . '.svg']
            );
        }
        /**
         * Add page tsconfig
         */
        if ((bool) $pizpalueConfiguration['enableDefaultPageTSconfig']) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
                "@import 'EXT:pizpalue/Configuration/TsConfig/Page/ContentElement/All.tsconfig'"
            );
        }
    }

    /**
     * Hook: DataHandler used to set image variants for content elements
     */
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']
        ['pizpalue'] = \Buepro\Pizpalue\Hook\DataHandlerHook::class;

    /**
     * Extension news
     */
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('news')) {
        if ((bool) $pizpalueConfiguration['enableDefaultPageTSconfig']) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
                "@import 'EXT:pizpalue/Extensions/news/Configuration/TsConfig/Page.tsconfig'"
            );
        }
    }

    /**
     * Extension eventnews
     */
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('eventnews')) {
        if ((bool) $pizpalueConfiguration['enableDefaultPageTSconfig']) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
                "@import 'EXT:pizpalue/Extensions/eventnews/Configuration/TsConfig/Page.tsconfig'"
            );
        }
    }

    /**
     * Upgrade wizards
     */
    $upgradeSteps = ['ContentElementXxl', 'ContentElementClasses', 'EmphasizeMedia'];
    foreach ($upgradeSteps as $upgradeStep) {
        $className = 'Buepro\\Pizpalue\\Updates\\' . $upgradeStep . 'Update';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][$className] = $className;
    }
})();
