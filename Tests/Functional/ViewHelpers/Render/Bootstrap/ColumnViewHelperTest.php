<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Functional\ViewHelpers\Render\Bootstrap;

use BK2K\BootstrapPackage\Utility\TypoScriptUtility;
use Buepro\Pizpalue\Structure\VariantsModifierStack;
use Buepro\Pizpalue\Tests\Functional\FunctionalFrontendTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * @todo Running more than one tests leads to unterminated run
 */
class ColumnViewHelperTest extends FunctionalFrontendTestCase
{
    private const TEMPLATE_PATH = 'EXT:pizpalue/Tests/Functional/ViewHelpers/Fixtures/Render/Bootstrap/Column.html';

    /**
     * @var non-empty-string[]
     */
    protected array $coreExtensionsToLoad = [
        'impexp',
        'rte_ckeditor',
        'seo',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        foreach (['pages', 'sys_template', 'tt_content'] as $table) {
            $this->importCSVDataSet(sprintf(
                '%s/db_table_%s.csv',
                __DIR__ . '/../../../Fixtures',
                $table
            ));
        }
        $this->setupFrontendSite(1);
        $this->setupFrontendController(1);
    }

    /** @test */
    public function variantsGetModified(): void
    {
        $doc = $this->getDOMDocument();
        $variantsModifierStack = GeneralUtility::makeInstance(VariantsModifierStack::class);
        $variantsModifierStack->resetStack();
        $initialVariants = $this->getReducedVariantsFromTypoScript();

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
            'role' => 'main',
        ]);
        $html = $view->render();
        $doc = new \DOMDocument();
        @$doc->loadHTML($html);
        return $doc;
    }

    private function getVariants(\DOMDocument $doc, string $id): array
    {
        $json = $doc->getElementById($id)->textContent ?? '';
        $extendedVariants = is_array($result = json_decode($json, true, 512, JSON_THROW_ON_ERROR)) ? $result : [];
        return $this->getReducedVariants($extendedVariants);
    }

    private function getReducedVariants(array $extendedVariants): array
    {
        return array_map(static function (array $variant): array {
            $result = [];
            if (isset($variant['breakpoint'])) {
                $result['breakpoint'] = $variant['breakpoint'];
            }
            if (isset($variant['width'])) {
                $result['width'] = $variant['width'];
            }
            return $result;
        }, $extendedVariants);
    }

    private function getReducedVariantsFromTypoScript(): array
    {
        $tsVariantsWithDotInKey = TypoScriptUtility::unflatten(TypoScriptUtility::getSetup($GLOBALS['TYPO3_REQUEST']))
        ['lib']['']['contentElement.']['settings.']['responsiveimages.']['variants.'] ?? [];
        $initialVariants = [];
        foreach ($tsVariantsWithDotInKey as $keyWithDot => $value) {
            $value['breakpoint'] = (int) $value['breakpoint'];
            $value['width'] = (int) $value['width'];
            $initialVariants[trim($keyWithDot, '.')] = $value;
        }
        unset($initialVariants['extrasmall']['breakpoint']);
        return $this->getReducedVariants($initialVariants);
    }
}
