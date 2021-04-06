<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

/**
 * Add fields to content elements
 */
(function () {
    /**
     * Define columns
     */
    if (1) {

        // Defines available classes for dropdown selector
        $classesItemsList = 'pp-bg-primary,pp-bg-secondary,pp-bg-complementary,pp-bg-light,pp-bg-dark,'
            . 'pp-bg-centercover,pp-bg-fixed,'
            . 'pp-card-primary,pp-card-secondary,pp-card-complementary,pp-card-light,pp-card-dark,'
            . 'pp-inner-margin,pp-inner-padding,pp-inner-bgwhite70,pp-inner-bggrey70,pp-inner-bgblack70,'
            . 'pp-gallery-item-left,pp-gallery-item-right,pp-gallery-item-join,pp-gallery-item-shadow,'
            . 'pp-image-overlay,'
            . 'pp-ce-overlaycard,'
            . 'pp-parent-height,pp-row-height,pp-row-child-height,'
            . 'pp-tile-scroll-y,'
            . 'pp-below-header';
        $classItems = [];
        foreach (explode(',', $classesItemsList) as $class) {
            $classItems[] = [
                'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.classes.' . $class,
                $class . ' '
            ];
        }

        // AOS scroll animations
        // @deprecated since 11.4.0
        // @todo remove support for AOS
        $aosAttributes = static function ()
        {
            // Defines a group of available AOS animations for dropdown selector
            $aosFadeList = 'fade,fade-up,fade-down,fade-left,fade-right,fade-up-right,fade-up-left,fade-down-right,fade-down-left';
            $aosFlipList = 'flip-up,flip-down,flip-left,flip-right';
            $aosSlideList = 'slide-up,slide-down,slide-left,slide-right';
            $aosZoomList = 'zoom-in,zoom-in-up,zoom-in-down,zoom-in-left,zoom-in-right,zoom-out,zoom-out-up,zoom-out-down,'
                . 'zoom-out-left,zoom-out-right';
            $aosAnimation = [];
            foreach (['fade' => $aosFadeList, 'flip' => $aosFlipList, 'slide' => $aosSlideList, 'zoom' => $aosZoomList]
                     as $name => $list) {
                $aosAnimation[$name] = [];
                foreach (explode(',', $list) as $animation) {
                    $aosAnimation[$name][] = ['AOS ' . $animation, 'data-aos="' . $animation . '" '];
                }
            }

            // Defines a group of available AOS settings for dropdown selector
            $aosAnchorPlacementList = 'top-bottom,top-center,top-top,center-bottom,center-center,center-top,bottom-bottom,bottom-center,'
                . 'bottom-top';
            $aosEasingList = 'linear,ease,ease-in,ease-out,ease-in-out,ease-in-back,ease-out-back,ease-in-out-back,'
                . 'ease-in-sine,ease-out-sine,ease-in-out-sine,ease-in-quad,ease-out-quad,ease-in-out-quad,'
                . 'ease-in-cubic, ease-out-cubic,ease-in-out-cubic,ease-in-quart,ease-out-quart,ease-in-out-quart';
            $aosSettings = [];
            foreach (['anchor-placement' => $aosAnchorPlacementList, 'easing' => $aosEasingList] as $name => $list) {
                $aosSettings[$name] = [];
                foreach (explode(',', $list) as $setting) {
                    $aosSettings[$name][] = ['AOS ' . $setting, 'data-aos-' . $name . '="' . $setting . '" '];
                }
            }

            // Merge AOS attributes
            return array_merge(
                $aosAnimation['fade'],
                [['---', '']],
                $aosAnimation['flip'],
                [['---', '']],
                $aosAnimation['slide'],
                [['---', '']],
                $aosAnimation['zoom'],
                [['---', '']],
                $aosSettings['anchor-placement'],
                [['---', '']],
                $aosSettings['easing'],
                [
                    ['---', ''],
                    ['AOS offset', 'data-aos-offset="120" '],
                    ['AOS duration', 'data-aos-duration="400" '],
                    ['AOS delay', 'data-aos-delay="0" '],
                    ['AOS anchor', 'data-aos-anchor="null" '],
                    ['AOS once', 'data-aos-once="false" ']
                ]
            );
        };

        // Twikito scroll animation
        $twikitoAttributes = static function()
        {
            return [
                ['Twikito animation', 'data-scroll="animate__pulse" '],
                ['Twikito repeat', 'data-scroll-repeat="true" '],
                ['Twikito offset', 'data-scroll-offset="200" '],
            ];
        };

        // Josh scroll animation (using animate.css)
        $joshAttributes = static function ()
        {
            return [
                ['Josh animation', 'data-josh-anim-name="pulse" '],
                ['Josh duration', 'data-josh-duration="1500ms" '],
                ['Josh delay', 'data-josh-delay="3.5s" '],
                ['Josh iteration', 'data-josh-iteration="infinite" '],
            ];
        };

        // Column definition
        $tmp_pizpalue_columns = [
            'tx_pizpalue_image_scaling' => [
                'exclude' => true,
                'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_scaling',
                'config' => [
                    'type' => 'text',
                    'default' => implode(',' . chr(10), ['xl: 1.0', 'lg: 1.0', 'md: 1.0', 'sm: 1.0', 'xs: 1.0']),
                ]
            ],
            'tx_pizpalue_image_variants' => [
                'exclude' => true,
                'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_variants',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'default' => 'variants',
                    'items' => [
                        ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_variants.content', 'variants'],
                        ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_variants.page', 'pageVariants'],
                    ],
                ],
            ],
            'tx_pizpalue_image_aspect_ratio' => [
                'exclude' => true,
                'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_aspect_ratio',
                'config' => [
                    'type' => 'text',
                    'default' => implode(',' . chr(10), ['xl: 0', 'lg: 0', 'md: 0', 'sm: 0', 'xs: 0']),
                    'valuePicker' => [
                        'items' => [
                            [
                                'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.undefined',
                                implode(',' . chr(10), ['xl: 0', 'lg: 0', 'md: 0', 'sm: 0', 'xs: 0'])
                            ],
                            ['2:1', implode(',' . chr(10), ['xl: 2.0', 'lg: 2.0', 'md: 2.0', 'sm: 2.0', 'xs: 2.0'])],
                            ['16:9', implode(',' . chr(10), ['xl: 1.7778', 'lg: 1.7778', 'md: 1.7778', 'sm: 1.7778', 'xs: 1.7778'])],
                            ['4:3', implode(',' . chr(10), ['xl: 1.3333', 'lg: 1.3333', 'md: 1.3333', 'sm: 1.3333', 'xs: 1.3333'])],
                            ['1:1', implode(',' . chr(10), ['xl: 1.0', 'lg: 1.0', 'md: 1.0', 'sm: 1.0', 'xs: 1.0'])],
                            ['3:4', implode(',' . chr(10), ['xl: 0.75', 'lg: 0.75', 'md: 0.75', 'sm: 0.75', 'xs: 0.75'])],
                            ['9:16', implode(',' . chr(10), ['xl: 0.5625', 'lg: 0.5625', 'md: 0.5625', 'sm: 0.5625', 'xs: 0.5625'])],
                            ['1:2', implode(',' . chr(10), ['xl: 0.5', 'lg: 0.5', 'md: 0.5', 'sm: 0.5', 'xs: 0.5'])]
                        ],
                    ],
                ],
            ],
            'tx_pizpalue_layout_breakpoint' => [
                'exclude' => true,
                'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.layoutBreakpoint',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'default' => '',
                    'items' => [
                        ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.layoutBreakpoint.all', ''],
                        ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.layoutBreakpoint.small', 'sm'],
                        ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.layoutBreakpoint.medium', 'md'],
                        ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.layoutBreakpoint.large', 'lg'],
                        ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.layoutBreakpoint.extralarge', 'xl'],
                    ],
                ],
            ],
            'tx_pizpalue_background_image_variants' => [
                'exclude' => true,
                'displayCond' => 'FIELD:frame_class:!=:none',
                'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.background_image_variants',
                'description' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.background_image_variants.description',
                'default' => 'pageVariants',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'default' => 'pageVariants',
                    'items' => [
                        ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_variants.content', 'variants'],
                        ['LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.image_variants.page', 'pageVariants'],
                    ],
                ],
            ],
            // @deprecated since 11.4.0
            // @todo remove support for inline background image
            'tx_pizpalue_bgmedia' => [
                'exclude' => true,
                'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.bgmedia',
                'description' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang.xlf:deprecatedItemNote',
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
                    ],
                ],
            ],
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
                'description' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.styleDescription',
                'config' => [
                    'type' => 'text',
                    'renderType' => 't3editor',
                    'rows' => 6,
                    'cols' => 50,
                ],
            ],
            'tx_pizpalue_attributes' => [
                'exclude' => true,
                'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.additionalAttributes',
                'description' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.additionalAttributesDescription',
                'config' => [
                    'type' => 'input',
                    'size' => 100,
                    'max' => 255,
                    'valuePicker' => [
                        'mode' => 'append',
                        'items' => array_merge(
                            [
                                ['▁▁▁▁▁▁▁▁▁▁▁▁▁▁▁▁▁▁', ''],
                                [' Twikito', ''],
                                ['▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔', '']
                            ],
                            $twikitoAttributes(),
                            [
                                ['▁▁▁▁▁▁▁▁▁▁▁▁▁▁▁▁▁▁', ''],
                                [' Josh', ''],
                                ['▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔', '']
                            ],
                            $joshAttributes(),
                            [
                                ['▁▁▁▁▁▁▁▁▁▁▁▁▁▁▁▁▁▁', ''],
                                [' AOS', ''],
                                ['LLL:EXT:pizpalue/Resources/Private/Language/locallang.xlf:deprecated', ''],
                                ['▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔', '']
                            ],
                            $aosAttributes()
                        )
                    ],
                ],
            ],
        ];

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
            'tt_content',
            $tmp_pizpalue_columns
        );
    }

    /**
     * Define palettes
     */
    if (1) {
        $GLOBALS['TCA']['tt_content']['palettes']['pizpalue_imagesize'] = [
            'showitem' => 'tx_pizpalue_image_scaling, tx_pizpalue_image_variants, tx_pizpalue_image_aspect_ratio',
        ];
        $GLOBALS['TCA']['tt_content']['palettes']['pizpalue_behaviour'] = [
            'showitem' => 'tx_pizpalue_animation',
        ];
        $GLOBALS['TCA']['tt_content']['palettes']['pizpalue_attributes'] = [
            'showitem' => '
            tx_pizpalue_classes,--linebreak--,
            tx_pizpalue_style,--linebreak--,
            tx_pizpalue_attributes
        ',
        ];
    }

    /**
     * Add background image to frames palette
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'frames',
        '--linebreak--, tx_pizpalue_background_image_variants, --linebreak--, tx_pizpalue_bgmedia',
        'after: background_image_options'
    );

    /**
     * Add tx_pizpalue_layout_breakpoint after layout field
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'frames',
        'tx_pizpalue_layout_breakpoint,--linebreak--',
        'after: layout'
    );

    /**
     * Add palettes content types
     */
    if (1) {
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
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--palette--;;pizpalue_imagesize',
            'image,textmedia,textpic',
            'before: imageorient'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            'tx_pizpalue_image_variants',
            'carousel,carousel_fullscreen,carousel_small',
            'after: pi_flexform'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            'tx_pizpalue_image_variants',
            'list',
            'after: list_type'
        );
    }
})();

/**
 * Extend existing fields
 */
(function () {
    /**
     * Adds complementary class to frame background (after secondary, in case it still exists)
     */
    $tcaItems = $GLOBALS['TCA']['tt_content']['columns']['background_color_class']['config']['items'];
    $items = [];
    $complementaryAdded = false;
    foreach ($tcaItems as [$value, $text]) {
        $items[] = [$value, $text];
        if ($value === 'secondary') {
            $items[] = ['complementary', 'complementary'];
            $complementaryAdded = true;
        }
    }
    if (!$complementaryAdded) {
        $items[] = ['complementary', 'complementary'];
    }
    $GLOBALS['TCA']['tt_content']['columns']['background_color_class']['config']['items'] = $items;
})();

/**
 * Configure existing fields
 */
(function () {
    unset($GLOBALS['TCA']['tt_content']['columns']['background_image']['displayCond'],
        $GLOBALS['TCA']['tt_content']['columns']['background_image_options']['displayCond']);
})();
