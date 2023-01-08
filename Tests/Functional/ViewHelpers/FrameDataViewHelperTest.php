<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Functional\ViewHelpers;

use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class FrameDataViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pizpalue/Tests/Functional/ViewHelpers/Fixtures/FrameData.html';

    /**
     * @var bool Speed up this test case, it needs no database
     */
    protected bool $initializeDatabase = false;

    /**
     * @var StandaloneView
     */
    protected $view;

    /**
     * @var AssetCollector
     */
    protected $assetCollector;

    protected array $contentData = [
        'uid' => 1,
        'layout' => 0,
        'frame_class' => 'default',
        'background_image' => 0,
        'tx_pizpalue_layout_breakpoint' => '',
        'tx_pizpalue_inner_space_before_class' => '',
        'tx_pizpalue_inner_space_after_class' => '',
        'tx_pizpalue_animation' => 0,
        'tx_pizpalue_classes' => 'test-1636104021 test-1636104031',
        'tx_pizpalue_inner_classes' => 'test-1663670419',
        'tx_pizpalue_style' => 'border-left-color: blue; border-right-color: green;',
        'tx_pizpalue_attributes' => 'data-1636104078="1" data-1636104091="2"',
    ];

    protected array $pizpalueConstants = [
        'seo' => [
            'optimizeLinkTargets' => 0,
        ],
        'animation' => [
            '1' => [
                'classes' => 'test-animation-1636113235',
                'styles' => 'border: red;',
                'attributes' => 'data-josh-anim-name="fadeInBottomLeft"',
            ],
            'josh' => [
                'initParams' => 'animateInMobile: false',
            ],
        ],
    ];

    /**
     * @var non-empty-string[]
     */
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/bootstrap_package',
        'typo3conf/ext/pizpalue',
    ];

    private function getDefaultExpected(): array
    {
        return [
            'classes' => ['test-1636104021', 'test-1636104031'],
            'innerClasses' => ['test-1663670419'],
            'styles' => ['border-left-color: blue', 'border-right-color: green'],
            'attributes' => ['data-1636104078="1"', 'data-1636104091="2"'],
            'isTile' => (bool)$this->contentData['layout'],
            'hasCssAnimation' => false,
            'hasScrollAnimation' => false,
            'optimizeLinkTargets' => (bool) $this->pizpalueConstants['seo']['optimizeLinkTargets'],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->view = GeneralUtility::makeInstance(StandaloneView::class);
        $this->view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $this->assetCollector = GeneralUtility::makeInstance(AssetCollector::class);
    }

    private function assertViewHelperRendering(array $data, array $pizpalueConstants, array $expected): void
    {
        $this->view->assignMultiple([
            'data' => $data,
            'pizpalueConstants' => $pizpalueConstants,
        ]);
        $html = $this->view->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//div[@id="inline"]');
        $actual = json_decode(trim((string)$node), true);
        self::assertSame($expected, $actual);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function tagOrInlineModeCanBeUsed(): void
    {
        $this->view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $this->view->assignMultiple([
            'data' => $this->contentData,
            'pizpalueConstants' => $this->pizpalueConstants,
        ]);
        $html = $this->view->render();
        $xml = new \SimpleXMLElement($html);
        foreach (['tag', 'inline'] as $id) {
            [$node] = $xml->xpath('//div[@id="' . $id . '"]');
            $actual = trim((string)$node);
            self::assertSame(json_encode($this->getDefaultExpected()), $actual);
        }
    }

    public function propertiesAreFormattedDataProvider(): array
    {
        $expected = $this->getDefaultExpected();
        $classesData = $this->contentData;
        $classesData['tx_pizpalue_classes'] = ' test-1636104021  test-1636104031 ';
        $innerClassesData = $this->contentData;
        $innerClassesData['tx_pizpalue_inner_classes'] = ' test-1663670419 ';
        $styleData = $this->contentData;
        $styleData['tx_pizpalue_style'] = ' border-left-color:blue ;border-right-color:  green; ';
        $attributesData = $this->contentData;
        $attributesData['tx_pizpalue_attributes'] = ' data-1636104078 ="1"   data-1636104091= "2" ';
        return [
            'classes spaces' => [$classesData, $this->pizpalueConstants, $expected],
            'inner classes spaces' => [$innerClassesData, $this->pizpalueConstants, $expected],
            'style spaces' => [$styleData, $this->pizpalueConstants, $expected],
            'attributes spaces' => [$attributesData, $this->pizpalueConstants, $expected],
        ];
    }

    /**
     * @dataProvider propertiesAreFormattedDataProvider
     * @test
     */
    public function propertiesAreFormatted(array $data, array $pizpalueConstants, array $expected): void
    {
        $this->assertViewHelperRendering($data, $pizpalueConstants, $expected);
    }

    public function classesAttributeContainsBackgroundClassDataProvider(): array
    {
        return [
            'no background image class on frame content' => [
                array_merge($this->contentData, ['background_image' => 0]),
                $this->pizpalueConstants,
                array_merge($this->getDefaultExpected(), ['classes' => ['test-1636104021', 'test-1636104031']]),
            ],
            'background image class on frame content' => [
                array_merge($this->contentData, ['background_image' => 1]),
                $this->pizpalueConstants,
                array_merge($this->getDefaultExpected(), ['classes' => ['pp-has-backgroundimage', 'test-1636104021', 'test-1636104031']]),
            ],
            'no background image class on frameless content' => [
                array_merge($this->contentData, ['frame_class' => 'none', 'background_image' => 0,
                    'tx_pizpalue_classes' => '', 'tx_pizpalue_style' => '', 'tx_pizpalue_attributes' => '']),
                $this->pizpalueConstants,
                array_merge($this->getDefaultExpected(), ['classes' => [], 'styles' => [], 'attributes' => []]),
            ],
            'background image class on frameless content' => [
                array_merge($this->contentData, ['frame_class' => 'none', 'background_image' => 1,
                    'tx_pizpalue_classes' => '', 'tx_pizpalue_style' => '', 'tx_pizpalue_attributes' => '']),
                $this->pizpalueConstants,
                array_merge($this->getDefaultExpected(), [
                    'classes' => ['pp-frameless-content', 'pp-type-', 'pp-has-backgroundimage'],
                    'styles' => [], 'attributes' => []
                ]),
            ],
        ];
    }

    /**
     * @dataProvider classesAttributeContainsBackgroundClassDataProvider
     * @test
     */
    public function classesAttributeContainsBackgroundClass(array $data, array $pizpalueConstants, array $expected): void
    {
        $this->assertViewHelperRendering($data, $pizpalueConstants, $expected);
    }

    /**
     * @test
     */
    public function styleIsIncludedInAssets(): void
    {
        $data = $this->contentData;
        $data['tx_pizpalue_style'] = '#self .frame-inner { border: green 1px solid; }';
        $expected = $this->getDefaultExpected();
        $expected['styles'] = [];
        $this->assertViewHelperRendering($data, $this->pizpalueConstants, $expected);
        self::assertArrayHasKey(
            'ppCe' . $data['uid'],
            $this->assetCollector->getInlineStyleSheets()
        );
    }

    public function animationsSetPropertiesAndIncludeAssetsDataProvider(): array
    {
        // Animation preset with josh
        $joshData = $this->contentData;
        $joshData['tx_pizpalue_animation'] = 1;
        $joshExpected = $this->getDefaultExpected();
        $joshExpected['hasScrollAnimation'] = true;
        $joshExpected['classes'][] = 'test-animation-1636113235';
        $joshExpected['classes'][] = 'josh-js';
        $joshExpected['styles'][] = 'border: red';
        $joshExpected['attributes'][] = 'data-josh-anim-name="fadeInBottomLeft"';

        // Animation with twikito
        $twikitoData = $this->contentData;
        $twikitoData['tx_pizpalue_attributes'] = 'data-scroll="animate__pulse"';
        $twikitoExpected = $this->getDefaultExpected();
        $twikitoExpected['hasScrollAnimation'] = true;
        $twikitoExpected['attributes'] = ['data-scroll="animate__animated animate__pulse"'];
        $twikitoExpected['attributes'][] = 'data-scroll-reverse="true"';
        return [
            'animation preset with josh' => [
                $joshData,
                $this->pizpalueConstants,
                $joshExpected,
                [
                    ['type' => 'JavaScript', 'id' => 'ppJosh'],
                    ['type' => 'InlineJavaScript', 'id' => 'ppJoshInit']
                ]
            ],
            'animation with twikito' => [
                $twikitoData,
                $this->pizpalueConstants,
                $twikitoExpected,
                [
                    ['type' => 'JavaScript', 'id' => 'ppTwikitoOnscroll'],
                    ['type' => 'InlineStyleSheet', 'id' => 'twikitoOnscroll']
                ]
            ],
        ];
    }

    /**
     * @dataProvider animationsSetPropertiesAndIncludeAssetsDataProvider
     * @test
     */
    public function animationsSetPropertiesAndIncludeAssets(
        array $data,
        array $pizpalueConstants,
        array $expectedData,
        array $expectedAnimationAssets
    ): void {
        $this->assertViewHelperRendering($data, $pizpalueConstants, $expectedData);
        foreach ($expectedAnimationAssets as $animationAsset) {
            $assets = $this->assetCollector->{'get' . $animationAsset['type'] . 's'}();
            self::assertArrayHasKey($animationAsset['id'], $assets);
        }
    }

    public function innerSpacingsSetPropertiesDataProvider(): array
    {
        $expectedClasses = $this->getDefaultExpected()['classes'];
        return [
            'inner spacing before' => [
                array_replace($this->contentData, ['tx_pizpalue_inner_space_before_class' => 'foo']),
                array_replace(
                    $this->getDefaultExpected(),
                    ['classes' => array_merge(['pp-inner-space-before-foo'], $expectedClasses)]
                ),
            ],
            'inner spacing after' => [
                array_replace($this->contentData, ['tx_pizpalue_inner_space_after_class' => 'bar']),
                array_replace(
                    $this->getDefaultExpected(),
                    ['classes' => array_merge(['pp-inner-space-after-bar'], $expectedClasses)]
                ),
            ],
            'inner spacing before and after' => [
                array_replace($this->contentData, [
                    'tx_pizpalue_inner_space_before_class' => 'foo',
                    'tx_pizpalue_inner_space_after_class' => 'bar',
                ]),
                array_replace(
                    $this->getDefaultExpected(),
                    ['classes' => array_merge(['pp-inner-space-before-foo', 'pp-inner-space-after-bar'], $expectedClasses)]
                ),
            ],
        ];
    }

    /**
     * @dataProvider innerSpacingsSetPropertiesDataProvider
     * @test
     */
    public function innerSpacingsSetProperties(array $data, array $expectedData): void
    {
        $this->assertViewHelperRendering($data, $this->pizpalueConstants, $expectedData);
    }
}
