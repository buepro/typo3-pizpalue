<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Unit\DataProcessing;

use Buepro\Pizpalue\DataProcessing\PostMenuProcessor;
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class PostMenuProcessorTest extends UnitTestCase
{
    use ProphecyTrait;

    /** @var array $newProperties */
    protected $newProperties = ['coreActive', 'isInRootLine', 'isShortcut', 'shortcutTargetIsCurrent', 'ppActive'];
    /** @var int $currentPageUid */
    protected $currentPageUid = 1;
    /** @var array $rootLine */
    protected $rootLine = [
        ['uid' => 1],
        ['uid' => 2],
        ['uid' => 3]
    ];
    /** @var array $initialProcessedData */
    protected $initialProcessedData = [
        'data' => [
            'uid' => 1,
            'doktype' => PageRepository::DOKTYPE_DEFAULT,
            'shortcut' => 2,
        ],
        'active' => 0,
    ];

    public function setUp(): void
    {
        parent::setUp();
        $tsfeProphecy = $this->prophesize(TypoScriptFrontendController::class);
        /** @var TypoScriptFrontendController $GLOBALS['TSFE'] */
        $GLOBALS['TSFE'] = $tsfeProphecy->reveal();
        $GLOBALS['TSFE']->rootLine = $this->rootLine;
        $this->setRequest($this->currentPageUid);
    }

    protected function setRequest(int $pageUid): void
    {
        $GLOBALS['TYPO3_REQUEST'] = (new ServerRequest())
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
            ->withAttribute('routing', new \TYPO3\CMS\Core\Routing\PageArguments($pageUid, '0', []))
            ->withQueryParams(['id' => $pageUid]);
    }

    protected function getActualItem(array $initialProcessedData, array $processorConfiguration =  []): array
    {
        return GeneralUtility::makeInstance(PostMenuProcessor::class)->process(
            new ContentObjectRenderer(),
            [],
            $processorConfiguration,
            $initialProcessedData
        );
    }

    /**
     * @test
     */
    public function itemContainsNewIntegerProperties(): void
    {
        $this->resetSingletonInstances = true;
        $actual = $this->getActualItem($this->initialProcessedData);
        foreach ($this->newProperties as $property) {
            self::assertArrayHasKey($property, $actual);
            self::assertIsInt($actual[$property]);
        }
    }

    /**
     * @test
     */
    public function assertCoreActiveProperty(): void
    {
        $this->resetSingletonInstances = true;
        $initial = $this->initialProcessedData;
        $initial['active'] = 0;
        $actual = $this->getActualItem($initial);
        self::assertSame(0, $actual['coreActive']);
        $initial['active'] = 1;
        $actual = $this->getActualItem($initial);
        self::assertSame(1, $actual['coreActive']);
    }

    /**
     * @test
     */
    public function assertIsInRootLineProperty(): void
    {
        $this->resetSingletonInstances = true;
        $initial = $this->initialProcessedData;
        foreach ($GLOBALS['TSFE']->rootLine as $pageRecord) {
            $initial['data']['uid'] = $pageRecord['uid'];
            $actual = $this->getActualItem($initial);
            self::assertSame(1, $actual['isInRootLine']);
        }
        $initial['data']['uid'] = 4;
        $actual = $this->getActualItem($initial);
        self::assertSame(0, $actual['isInRootLine']);
    }

    /**
     * @test
     */
    public function assertIsShortcutProperty(): void
    {
        $this->resetSingletonInstances = true;
        $initial = $this->initialProcessedData;
        $initial['data']['doktype'] = PageRepository::DOKTYPE_DEFAULT;
        $actual = $this->getActualItem($initial);
        self::assertSame(0, $actual['isShortcut']);
        $initial['data']['doktype'] = PageRepository::DOKTYPE_SHORTCUT;
        $actual = $this->getActualItem($initial);
        self::assertSame(1, $actual['isShortcut']);
    }

    public static function assertShortcutTargetIsCurrentDataProvider(): array
    {
        return [
            'default, target current' => [1, PageRepository::DOKTYPE_DEFAULT, 1, 0],
            'default, target not current' => [1, PageRepository::DOKTYPE_DEFAULT, 2, 0],
            'shortcut, target current' => [1, PageRepository::DOKTYPE_SHORTCUT, 1, 1],
            'shortcut, target not current' => [1, PageRepository::DOKTYPE_SHORTCUT, 2, 0],
        ];
    }

    /**
     * @dataProvider assertShortcutTargetIsCurrentDataProvider
     * @test
     */
    public function assertShortcutTargetIsCurrent(
        int $currentPageUid,
        int $doktype,
        int $shortcut,
        int $expected
    ): void {
        $this->resetSingletonInstances = true;
        $initial = $this->initialProcessedData;
        /** @extensionScannerIgnoreLine */
        $GLOBALS['TSFE']->id = $currentPageUid;
        $initial['data']['doktype'] = $doktype;
        $initial['data']['shortcut'] = $shortcut;
        $actual = $this->getActualItem($initial);
        self::assertSame($expected, $actual['shortcutTargetIsCurrent']);
    }

    public static function assertPpActivePropertyDataProvider(): array
    {
        return [
            'default, not in root line' => [3, 123, PageRepository::DOKTYPE_DEFAULT, 1, 0],
            'default, in root line 1' => [3, 1, PageRepository::DOKTYPE_DEFAULT, 1, 1],
            'default, in root line 2' => [3, 2, PageRepository::DOKTYPE_DEFAULT, 1, 1],
            'default, in root line 3' => [3, 3, PageRepository::DOKTYPE_DEFAULT, 1, 1],
            'shortcut, in root line' => [3, 2, PageRepository::DOKTYPE_SHORTCUT, 123, 1],
            'shortcut, not in root line' => [3, 12, PageRepository::DOKTYPE_SHORTCUT, 123, 0],
            'shortcut, target is current' => [3, 12, PageRepository::DOKTYPE_SHORTCUT, 3, 1],
        ];
    }

    /**
     * @dataProvider assertPpActivePropertyDataProvider
     * @test
     */
    public function assertPpActiveProperty(
        int $currentPageUid,
        int $uid,
        int $doktype,
        int $shortcut,
        int $expected
    ):void {
        $this->resetSingletonInstances = true;
        $this->setRequest($currentPageUid);
        $initial = $this->initialProcessedData;
        $initial['data']['uid'] = $uid;
        $initial['data']['doktype'] = $doktype;
        $initial['data']['shortcut'] = $shortcut;
        $actual = $this->getActualItem($initial);
        self::assertSame($expected, $actual['ppActive']);
    }

    public function assertActivePropertyConfiguration(): void
    {
        $this->resetSingletonInstances = true;
        $this->setRequest(3);
        $initial = $this->initialProcessedData;
        $initial['active'] = 123;
        $initial['foo'] = 246;
        $initial['data']['uid'] = 123;
        $initial['data']['doktype'] = PageRepository::DOKTYPE_SHORTCUT;
        $initial['data']['shortcut'] = 3;
        $actual = $this->getActualItem($initial);
        self::assertSame($actual['ppActive'], $actual['active']);
        $actual = $this->getActualItem($initial, ['activeProperty' => 'foo']);
        self::assertSame($initial['foo'], $actual['active']);
    }
}
