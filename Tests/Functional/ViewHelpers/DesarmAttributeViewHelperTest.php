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

class DesarmAttributeViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pizpalue/Tests/Functional/ViewHelpers/Fixtures/DesarmAttribute.html';

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
        return [
            'normal class attribute' => ['class="pp-test"', 'class="pp-test"'],
            'accepted attribute' => ['class="pp-test" style="border:red;" data-test="ok"', 'class="pp-test" style="border:red;" data-test="ok"'],
            'non accepted attribute' => ['class="pp-test" greeting="hi"', 'class="pp-test"'],
            'trim spaces' => ['  class="pp-test "   style=" border:red;"', 'class="pp-test" style="border:red;"'],
            'event attribute' => ['onclick="javascript(alert(\'Hacked!\'))"', ''],
            'js' => ['class="pp-test"><script>alert(\'Hacked!\');</script><div ', 'class="pp-test"'],
        ];
    }

    /**
     * @dataProvider renderDataProvider
     * @test
     */
    public function render(string $value, string $expected): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assign('value', $value);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        foreach (['tag-child', 'tag-attribute', 'inline-child', 'inline-property'] as $id) {
            [$node] = $xml->xpath('//div[@id="' . $id . '"]');
            $actual = trim((string)$node);
            self::assertSame($expected, $actual);
        }
    }
}
