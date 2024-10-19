<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Functional\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class FilterArrayViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pizpalue/Tests/Functional/ViewHelpers/Fixtures/FilterArray.html';

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

    public function renderDataProvider(): array
    {
        $sourceArray = [
            ['name' => 'Name 1', 'phone' => 'Phone 1', 'note' => 'Note 1'],
            ['gender' => 'm', 'name' => 'Name 2', 'phone' => 'Phone 2'],
        ];
        return [
            'filter for name' => [
                $sourceArray,
                'name',
                json_encode([['name' => 'Name 1'], ['name' => 'Name 2']]),
            ],
            'filter for name and phone' => [
                $sourceArray,
                'name,phone',
                json_encode([
                    ['name' => 'Name 1', 'phone' => 'Phone 1'],
                    ['name' => 'Name 2', 'phone' => 'Phone 2'],
                ]),
            ],
            'filter for name and phone with spaces' => [
                $sourceArray,
                ' name , phone ',
                json_encode([
                    ['name' => 'Name 1', 'phone' => 'Phone 1'],
                    ['name' => 'Name 2', 'phone' => 'Phone 2'],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider renderDataProvider
     * @test
     */
    public function render(array $source, string $keylist, string $expected): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assignMultiple([
            'source' => $source,
            'keylist' => $keylist,
        ]);
        $actual = trim($view->render());
        self::assertSame($expected, $actual);
    }
}
