<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Functional\ViewHelpers\Data;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ImageVariantsTextToArrayViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pizpalue/Tests/Functional/ViewHelpers/Fixtures/Data/ImageVariantsTextToArray.html';

    /**
     * @var bool Speed up this test case, it needs no database
     */
    protected $initializeDatabase = false;

    /**
     * @var non-empty-string[]
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/bootstrap_package',
        'typo3conf/ext/pizpalue',
    ];

    public function renderDataProvider(): array
    {
        $defaultVariants = [
            'default' => 0,
            'xlarge' => 0,
            'large' => 0,
            'medium' => 0,
            'small' => 0,
            'extrasmall' => 0,
        ];
        return [
            'missing text' => [null, null, $defaultVariants],
            'empty text' => ['', null, $defaultVariants],
            'empty text with default' => ['', 0.5, array_fill_keys(array_keys($defaultVariants), 0.5)],
            'default in text' => ['default: 0.5', null, array_fill_keys(array_keys($defaultVariants), 0.5)],
            'one line' => ['md: 0.5', null, array_replace($defaultVariants, ['medium' => 0.5])],
            'one line with spaces' => [' md :0.5 ', null, array_replace($defaultVariants, ['medium' => 0.5])],
            'some definitions with default in text' => [
                'default: 0.8, xxl: 0.6, xl: 0.7',
                null,
                [
                    'default' => 0.6,
                    'xlarge' => 0.7,
                    'large' => 0.8,
                    'medium' => 0.8,
                    'small' => 0.8,
                    'extrasmall' => 0.8,
                ]
            ],
            'some definitions with default in vh and text' => [
                'default: 0.8, xxl: 0.6, xl: 0.7',
                0.3,
                [
                    'default' => 0.6,
                    'xlarge' => 0.7,
                    'large' => 0.8,
                    'medium' => 0.8,
                    'small' => 0.8,
                    'extrasmall' => 0.8,
                ]
            ],
        ];
    }

    /**
     * @dataProvider renderDataProvider
     * @test
     */
    public function render(?string $text, ?float $default, array $expected): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assignMultiple([
            'text' => $text,
            'default' => $default
        ]);
        $html = trim($view->render());
        self::assertSame($expected, json_decode($html, true));
    }
}
