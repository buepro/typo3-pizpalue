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

class ImageVariantsViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pizpalue/Tests/Functional/ViewHelpers/Fixtures/Data/ImageVariants.html';

    /**
     * @var bool Speed up this test case, it needs no database
     */
    protected bool $initializeDatabase = false;

    /**
     * @var non-empty-string[]
     */
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/bootstrap_package',
        'typo3conf/ext/pizpalue',
    ];

    /**
     * @test
     */
    public function render(): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $variants = [
            'default' => [
                'aspectRatio' => 1
            ],
            'xlarge' => [
                'aspectRatio' => 1
            ],
            'large' => [
                'aspectRatio' => 1
            ],
            'medium' => [
                'aspectRatio' => 1
            ],
            'small' => [
                'aspectRatio' => 1
            ],
            'extrasmall' => [
                'aspectRatio' => 1
            ],
        ];
        $aspectRatio = [
            'default' => 0.8,
            'xlarge' => 0.7,
            'large' => 0.6,
            'medium' => 0.5,
            'small' => 0.4,
            'extrasmall' => 0.3,
        ];
        $expected = [
            'default' => [
                'aspectRatio' => 0.8
            ],
            'xlarge' => [
                'aspectRatio' => 0.7
            ],
            'large' => [
                'aspectRatio' => 0.6
            ],
            'medium' => [
                'aspectRatio' => 0.5
            ],
            'small' => [
                'aspectRatio' => 0.4
            ],
            'extrasmall' => [
                'aspectRatio' => 0.3
            ],
        ];
        $view->assignMultiple([
            'variants' => $variants,
            'aspectRatio' => $aspectRatio
        ]);
        $html = trim($view->render());
        self::assertSame($expected, json_decode($html, true));
    }
}
