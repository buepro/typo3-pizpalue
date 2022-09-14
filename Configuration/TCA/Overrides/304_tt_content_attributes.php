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
    $classesItemsList = 'bg-primary,bg-secondary,bg-complementary,bg-light,bg-dark,'
        . 'pp-inner-margin,pp-inner-padding,pp-inner-bgwhite70,pp-inner-bggrey70,pp-inner-bgblack70,'
        . 'pp-inner-panel-primary,pp-inner-panel-secondary,pp-inner-panel-complementary,pp-inner-panel-tertiary,'
        . 'pp-inner-panel-quaternary,pp-inner-panel-light,pp-inner-panel-dark,'
        . 'pp-gallery-item-left,pp-gallery-item-right,pp-gallery-item-join,pp-gallery-item-shadow,'
        . 'pp-image-overlay,'
        . 'pp-tile-scroll-y,'
        . 'pp-below-header';
    $classItems = [];
    foreach (explode(',', $classesItemsList) as $class) {
        $value = $class;
        if (strpos($class, 'pp-inner-panel') === 0) {
            $value = 'pp-inner-panel ' . $class;
        }
        $classItems[] = [
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
