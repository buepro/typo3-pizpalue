<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

/**
 * @see https://docs.typo3.org/typo3cms/extensions/core/Changelog/9.0/Important-82692-GuidelinesForExtensionFiles.html
 */
defined('TYPO3_MODE') or die('Access denied.');

/**
 * Configure BackendLayout
 */
if (1) {
    if (class_exists(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)) {
        // Add BackendLayouts BackendLayouts for the BackendLayout DataProvider
        $extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
        );
        $bootstrapPackageConfiguration = $extensionConfiguration->get('bootstrap_package');
    } else {
        // Fallback for TYPO3 V8
        // @extensionScannerIgnoreLine
        $bootstrapPackageConfiguration = $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['bootstrap_package'];
        if (!is_array($bootstrapPackageConfiguration)) {
            $bootstrapPackageConfiguration = unserialize($bootstrapPackageConfiguration);
        }
    }
    if (!$bootstrapPackageConfiguration['disablePageTsBackendLayouts']) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:pizpalue/Configuration/TsConfig/Page/Mod/WebLayout/BackendLayouts.tsconfig">'
        );
    }
}

/**
 * Configure RTE
 */
if (1) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:pizpalue/Configuration/TsConfig/Page/RTE.tsconfig">'
    );

    // Add default RTE configuration
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['pizpalue'] = 'EXT:pizpalue/Configuration/RTE/Default.yaml';
}

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
 * Backend styling TYPO3 8
 */
if (TYPO3_MODE === 'BE' && !class_exists(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)) {
    //Configure Backend Extension
    if (!is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend'])) {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend'] = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend']);
    }
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend']['loginLogo'] = 'EXT:pizpalue/Resources/Public/Images/backend-login-logo.png';
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend']['loginBackgroundImage'] = 'EXT:pizpalue/Resources/Public/Images/backend-login-background.jpg';
    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend'])) {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend'] = serialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['backend']);
    }
}

/**
 * Language menu for TYPO3 8
 */
if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < 9000000) {
    TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(implode(LF, [
        'config.language.default {',
        '    enable = 1',
        '    title = German',
        '    nav_title = Deutsch',
        '    locale = de_CH.UTF-8',
        '    language_isocode = de',
        '    hreflang = de-CH',
        '    direction = ltr',
        '}'
    ]));
}

/**
 * Configure gridelements
 */
if (1) {
    /**
     * Register icons for gridelements
     */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $icons = ['ppContainerFixed', 'ppContainer', 'ppColumns2', 'ppColumns3', 'ppColumns4', 'ppTabs', 'ppAccordion'];
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
