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
    // Defines available items for (outer) classes dropdown selector
    $classesItemsList = 'pp-gallery-item-center,pp-gallery-item-left,pp-gallery-item-right,'
        . 'pp-gallery-item-join,pp-gallery-item-shadow,'
        . 'pp-image-overlay,'
        . 'pp-row-height,'
        . 'pp-tile-scroll-y,'
        . 'pp-below-header';
    $classItems = [];
    foreach (explode(',', $classesItemsList) as $class) {
        $value = $class;
        $classItems[] = [
            'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.classes.' . $class,
            $value . ' '
        ];
    }

    // Defines available items for inner classes dropdown selector
    $innerClassesItemsList = 'pp-margin,pp-margin-sm,pp-padding,pp-padding-sm,pp-bg-gray-600,opacity-75,'
        . 'pp-panel-primary,pp-panel-secondary,pp-panel-complementary,pp-panel-tertiary,'
        . 'pp-panel-quaternary,pp-panel-light,pp-panel-dark';
    $innerClassItems = [];
    foreach (explode(',', $innerClassesItemsList) as $class) {
        $value = $class;
        if (strpos($class, 'pp-panel') === 0) {
            $value = 'pp-panel ' . $class;
        }
        $innerClassItems[] = [
            'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.classes.' . $class,
            $value . ' '
        ];
    }

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
            'description' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.classes.description',
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
        'tx_pizpalue_inner_classes' => [
            'exclude' => true,
            'label' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.inner_classes',
            'description' => 'LLL:EXT:pizpalue/Resources/Private/Language/locallang_db.xlf:tx_pizpalue_ttc.inner_classes.description',
            'displayCond' => 'FIELD:frame_class:!=:none',
            'config' => [
                'type' => 'input',
                'size' => 100,
                'max' => 255,
                'valuePicker' => [
                    'mode' => 'append',
                    'items' => $innerClassItems,
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
                        $joshAttributes()
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
            tx_pizpalue_inner_classes,--linebreak--,
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
