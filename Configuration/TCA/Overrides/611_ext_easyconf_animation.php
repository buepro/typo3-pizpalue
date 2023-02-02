<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Buepro\Easyconf\Mapper\TypoScriptConstantMapper;
use Buepro\Easyconf\Utility\TcaUtility;

defined('TYPO3') or die('Access denied.');

if (!\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('easyconf')) {
    return;
}

(static function () {
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_animation.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];

    /**
     * Properties
     */
    $animationProperties = 'animateCss.includeAlways, josh.includeAlways, twikito.includeAlways';

    /**
     * Define columns
     */
    $propertyMaps = [
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'pizpalue.animation',
            $animationProperties,
            'animation'
        ),
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteAnimation' => TcaUtility::getPalette(
            $animationProperties,
            'animation',
            3
        ),
    ]);
    $tca['palettes']['paletteAnimation']['description'] = $l10nFile . ':paletteAnimation.description';

    /**
     * Modify columns
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        $animationProperties,
        ['config' => ['type' => 'check', 'renderType' => 'checkboxToggle']],
        'animation'
    );

    unset($tca);
})();
