<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Buepro\Easyconf\Mapper\EasyconfMapper;
use Buepro\Easyconf\Mapper\TypoScriptConstantMapper;
use Buepro\Easyconf\Utility\TcaUtility;
use Buepro\Pizpalue\Easyconf\EventHandler\PersistService\SocialNetworkService;

defined('TYPO3') or die('Access denied.');

(static function () {
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_social.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];

    /**
     * Properties
     */
    $socialControlProperties = 'enable';
    $socialChannelProperties = implode(', ', SocialNetworkService::$socialChannels);

    /**
     * Define columns
     */
    $propertyMaps = [
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'page.theme.socialmedia',
            $socialControlProperties,
            'social'
        ),
        TcaUtility::getPropertyMap(
            EasyconfMapper::class,
            'pizpalue.social',
            $socialChannelProperties,
            'social_channel'
        ),
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteSocialControl' => TcaUtility::getPalette($socialControlProperties, 'social'),
        'paletteSocialChannel' => TcaUtility::getPalette($socialChannelProperties, 'social_channel'),
    ]);
    $tca['palettes']['paletteSocialChannel']['description'] = $l10nFile . ':paletteSocialChannel.description';

    /**
     * Modify columns
     */
    TcaUtility::modifyColumns(
        $tca['columns'],
        'enable',
        ['config' => ['type' => 'check', 'renderType' => 'checkboxToggle']],
        'social'
    );
    TcaUtility::modifyColumns(
        $tca['columns'],
        $socialChannelProperties,
        ['config' => ['renderType' => 'inputLink']],
        'social_channel'
    );

    unset($tca);
})();
