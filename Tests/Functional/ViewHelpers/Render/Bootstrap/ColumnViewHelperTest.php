<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Functional\ViewHelpers\Render\Bootstrap;

use Buepro\Pizpalue\Structure\VariantsModifierStack;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ColumnViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pizpalue/Tests/Functional/ViewHelpers/Fixtures/Render/Bootstrap/Column.html';

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

    /** @test */
    public function variantsGetModified(): void
    {
        $doc = $this->getDOMDocument();
        $variantsModifierStack = GeneralUtility::makeInstance(VariantsModifierStack::class);
        $variantsModifierStack->resetStack();
        $initialVariants = $variantsModifierStack->getVariants([]);
        $outerVariants = array_replace_recursive($initialVariants, [
            'default' => ['width' => (int)ceil($initialVariants['default']['width'] / 12 * 8)],
            'xlarge' => ['width' => (int)ceil($initialVariants['xlarge']['width'] / 12 * 8)],
            'large' => ['width' => (int)ceil($initialVariants['large']['width'] / 12 * 8)],
        ]);
        $innerVariants = array_replace_recursive($outerVariants, [
            'default' => ['width' => (int)ceil($initialVariants['default']['width'] / 12 * 8 / 12 * 6)],
            'xlarge' => ['width' => (int)ceil($initialVariants['xlarge']['width'] / 12 * 8 / 12 * 6)],
        ]);
        self::assertSame($initialVariants, $this->getVariants($doc, 'initial-variants'));
        self::assertSame($outerVariants, $this->getVariants($doc, 'initial-outer-variants'));
        self::assertSame($innerVariants, $this->getVariants($doc, 'inner-variants'));
        self::assertSame($outerVariants, $this->getVariants($doc, 'final-outer-variants'));
        self::assertSame($initialVariants, $this->getVariants($doc, 'final-variants'));
    }

    /** @test */
    public function tagNameAttribute(): void
    {
        $doc = $this->getDOMDocument();
        /** @phpstan-ignore-next-line */
        self::assertSame('div', $doc->getElementById('outer')->tagName);
        /** @phpstan-ignore-next-line */
        self::assertSame('main', $doc->getElementById('tag-test')->tagName);
    }

    /** @test */
    public function roleAttribute(): void
    {
        $doc = $this->getDOMDocument();
        /** @phpstan-ignore-next-line */
        self::assertSame('main', $doc->getElementById('role-test')->getAttribute('role'));
    }

    private function getDOMDocument(): \DOMDocument
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assignMultiple([
            'outerClass' => 'col-lg-8',
            'innerClass' => 'col-xl-6',
            'tagName' => 'main',
            'role' => 'main'
        ]);
        $html = $view->render();
        $doc = new \DOMDocument();
        @$doc->loadHTML($html);
        return $doc;
    }

    private function getVariants(\DOMDocument $doc, string $id): array
    {
        $json = $doc->getElementById($id)->textContent ?? '';
        return is_array($result = json_decode($json, true, 512, JSON_THROW_ON_ERROR)) ? $result : [];
    }
}
