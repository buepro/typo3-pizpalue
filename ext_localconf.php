<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3_MODE') || die();

(function () {
    /**
     * Make bootstrap_package configuration accessible
     */
    if (1) {
        $extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
        );
        $bootstrapPackageConfiguration = $extensionConfiguration->get('bootstrap_package');
    }

    /**
     * PageTS
     */
    if (1) {

        // Add BackendLayouts for the BackendLayout DataProvider
        if (!(bool) $bootstrapPackageConfiguration['disablePageTsBackendLayouts']) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
                '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:pizpalue/Configuration/TsConfig/Page/Mod/WebLayout/BackendLayouts.tsconfig">'
            );
        }

        // RTE
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:pizpalue/Configuration/TsConfig/Page/RTE.tsconfig">'
        );
    }

    /**
     * Add default RTE configuration for pizpalue
     */
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['pizpalue'] = 'EXT:pizpalue/Configuration/RTE/Default.yaml';

    /***************
     * Register "pp" as global fluid namespace
     */
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['pp'][] = 'Buepro\\Pizpalue\\ViewHelpers';

    /**
     * Backend styling TYPO3 9
     */
    if (TYPO3_MODE === 'BE') {
        $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
        $signalSlotDispatcher->connect(
            \TYPO3\CMS\Extensionmanager\Utility\InstallUtility::class,
            'afterExtensionInstall',
            \Buepro\Pizpalue\Service\BrandingService::class,
            'setBackendStyling'
        );
    }

    /**
     * Configure gridelements
     */
    if (1) {
        /**
         * Register icons for gridelements
         */
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $icons = ['ppContainerFixed', 'ppContainer', 'ppColumns2', 'ppColumns3', 'ppColumns4', 'ppTabs', 'ppAccordion',
            'ppTileUnit'];
        foreach ($icons as $iconName) {
            $iconRegistry->registerIcon(
                'tx-pizpalue-' . $iconName,
                \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
                ['source' => 'EXT:pizpalue/Resources/Public/Icons/Gridelements/' . $iconName . '.svg']
            );
        }
    }

    /**
     * Content elements
     */
    if (1) {
        /**
         * Register icons
         */
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $icons = ['modal-dialog', 'list-categorized-content'];
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
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:pizpalue/Configuration/TsConfig/Page/ContentElement/All.tsconfig">'
        );
    }

    /**
     * Adjust content element form in BE
     *
     * Modify flexform fields since core 8.5 via formEngine: Inject a data provider
     * between TcaFlexPrepare and TcaFlexProcess
     *
     */
    if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 8005000) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord']
        [\Buepro\Pizpalue\Backend\FormDataProvider\ContentFormDataProvider::class] = [
            'depends' => [
                \TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexPrepare::class,
            ],
            'before' => [
                \TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexProcess::class,
            ],
        ];
    }

    /**
     * After extension installation handler
     */
    $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
    $signalSlotDispatcher->connect(
        \TYPO3\CMS\Extensionmanager\Utility\InstallUtility::class,
        'afterExtensionInstall',
        \Buepro\Pizpalue\Slot\ExtensionInstallUtility::class,
        'afterExtensionInstall'
    );
})();
