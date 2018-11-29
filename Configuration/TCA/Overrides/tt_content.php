<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3_MODE') || die();

$GLOBALS['TCA']['tt_content']['palettes']['pizpalue_attributes'] = [
    'showitem' => '
        tx_pizpalue_classes,--linebreak--,
        tx_pizpalue_style,--linebreak--,
        tx_pizpalue_attributes
    ',
];

$GLOBALS['TCA']['tt_content']['palettes']['pizpalue_behaviour'] = [
    'showitem' => 'tx_pizpalue_animation',
];

// Class items for column definition
$classesItemsList = 'pp-parent-height,pp-row-height,pp-row-child-height,pp-ce-background,pp-ce-bgfixed,'
    . 'pp-content-margin,pp-content-padding,pp-content-bgwhite70,pp-content-bggrey70,pp-content-bgblack70,'
    . 'pp-gallery-item-left,pp-gallery-item-right,pp-gallery-item-join';
$classItems = [];
foreach(explode(',',$classesItemsList) as $class) {
    $classItems[] = [
        'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.classes.' . $class,
        $class . ' '
    ];
};

// Attribute items for column definition
$aosFadeList = 'fade,fade-up,fade-down,fade-left,fade-right,fade-up-right,fade-up-left,fade-down-right,fade-down-left';
$aosFlipList = 'flip-up,flip-down,flip-left,flip-right';
$aosSlideList = 'slide-up,slide-down,slide-left,slide-right';
$aosZoomList = 'zoom-in,zoom-in-up,zoom-in-down,zoom-in-left,zoom-in-right,zoom-out,zoom-out-up,zoom-out-down,'
    . 'zoom-out-left,zoom-out-right';
$aosAnimation = [];
foreach(['fade' => $aosFadeList,'flip' => $aosFlipList,'slide' => $aosSlideList,'zoom' => $aosZoomList]
        as $name => $list) {
    $aosAnimation[$name] = [];
    foreach(explode(',',$list) as $animation) {
        $aosAnimation[$name][] = [$animation, 'data-aos="' . $animation . '" '];
    }
}

$aosAnchorPlacementList = 'top-bottom,top-center,top-top,center-bottom,center-center,center-top,bottom-bottom,bottom-center,'
    . 'bottom-top';
$aosEasingList = 'linear,ease,ease-in,ease-out,ease-in-out,ease-in-back,ease-out-back,ease-in-out-back,'
    . 'ease-in-sine,ease-out-sine,ease-in-out-sine,ease-in-quad,ease-out-quad,ease-in-out-quad,'
    . 'ease-in-cubic, ease-out-cubic,ease-in-out-cubic,ease-in-quart,ease-out-quart,ease-in-out-quart';
$aosSettings = [];
foreach(['anchor-placement' => $aosAnchorPlacementList, 'easing' => $aosEasingList] as $name => $list) {
    $aosSettings[$name] = [];
    foreach(explode(',',$list) as $setting) {
        $aosSettings[$name][] = [$setting, 'data-aos-' .$name . '="' . $setting . '" '];
    }
}

// Column definition
$tmp_pizpalue_columns = [

    'tx_pizpalue_classes' => [
        'exclude' => true,
        'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.classes',
        'config' => [
            'type' => 'input',
            'size' => 100,
            'max' => 255,
            'valuePicker' => [
                'mode' => 'append',
                'items' => $classItems,
            ],
        ],
    ],
    'tx_pizpalue_style' => [
        'exclude' => true,
        'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.style',
        'config' => [
            'type' => 'input',
            'size' => 100,
            'max' => 255,
            'eval' => 'trim'
        ],
    ],
    'tx_pizpalue_attributes' => [
        'exclude' => true,
        'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.additionalAttributes',
        'config' => [
            'type' => 'input',
            'size' => 100,
            'max' => 255,
            'valuePicker' => [
                'mode' => 'append',
                'items' => array_merge(
                    $aosAnimation['fade'],
                    [['---','']],$aosAnimation['flip'],
                    [['---','']],$aosAnimation['slide'],
                    [['---','']],$aosAnimation['zoom'],
                    [['---','']],$aosSettings['anchor-placement'],
                    [['---','']],$aosSettings['easing'],
                    [
                        ['---',''],
                        ['offset','data-aos-offset="120" '],
                        ['duration','data-aos-duration="400" '],
                        ['delay','data-aos-delay="0" '],
                        ['anchor','data-aos-anchor="null" '],
                        ['once','data-aos-once="false" ']
                    ]
                )
            ],
        ],
    ],
    'tx_pizpalue_bgmedia' => [
        'exclude' => true,
        'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.bgmedia',
        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
            'tx_pizpalue_bgmedia',
            [
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                ],
                'maxitems' => 1,
                'overrideChildTca' => [
                    'types' => [
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => 'crop,--palette--;;filePalette',
                        ]
                    ],
                ],
            ],
            $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
        ),
    ],
    'tx_pizpalue_animation' => [
        'exclude' => true,
        'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.animation',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'default' => 0,
            'items' => [
                ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.animationNone', 0],
                ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.animation1', 1],
                ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.animation2', 2],
                ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.animation3', 3],
                ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.animation4', 4],
            ],
        ],
    ],

];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tt_content',
    $tmp_pizpalue_columns
);

$GLOBALS['TCA']['tt_content']['palettes']['frames']['showitem'] .= '
    --linebreak--,
    tx_pizpalue_bgmedia,
';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--palette--;LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.behaviour;pizpalue_behaviour',
    '',
    'after: tx_pizpalue_bgmedia'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--palette--;LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.attributes;pizpalue_attributes',
    '',
    'before: sectionIndex'
);



