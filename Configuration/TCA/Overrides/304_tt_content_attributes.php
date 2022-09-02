<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

/**
 * Add attribute fields to content elements
 */
(static function (): void {
    // Defines available classes for dropdown selector
    $classesItemsList = 'pp-bg-primary,pp-bg-secondary,pp-bg-complementary,pp-bg-light,pp-bg-dark,'
        . 'pp-bg-centercover,pp-bg-fixed,'
        . 'pp-card-primary,pp-card-secondary,pp-card-complementary,pp-card-light,pp-card-dark,'
        . 'pp-inner-margin,pp-inner-padding,pp-inner-bgwhite70,pp-inner-bggrey70,pp-inner-bgblack70,'
        . 'pp-gallery-item-left,pp-gallery-item-right,pp-gallery-item-join,pp-gallery-item-shadow,'
        . 'pp-image-overlay,'
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
    $aosAttributes = static function () {
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
    $twikitoAttributes = static function (): array {
        return [
            ['Twikito animation', 'data-scroll="animate__pulse" '],
            ['Twikito repeat', 'data-scroll-repeat="true" '],
            ['Twikito offset', 'data-scroll-offset="200" '],
        ];
    };

    // Josh scroll animation (using animate.css)
    $joshAttributes = static function (): array {
        return [
            ['Josh animation', 'data-josh-anim-name="pulse" '],
            ['Josh duration', 'data-josh-duration="1500ms" '],
            ['Josh delay', 'data-josh-anim-delay="3.5s" '],
            ['Josh iteration', 'data-josh-iteration="infinite" '],
        ];
    };

    $attributeColumns = [
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
                'eval' => 'Buepro\\Pizpalue\\UserFunction\\FormEngine\\CssEval',
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
        $attributeColumns
    );

    $GLOBALS['TCA']['tt_content']['palettes']['pizpalue_attributes'] = [
        'showitem' => '
            tx_pizpalue_classes,--linebreak--,
            tx_pizpalue_style,--linebreak--,
            tx_pizpalue_attributes
        ',
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--palette--;LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.attributes;pizpalue_attributes',
        '',
        'before: sectionIndex'
    );
})();
