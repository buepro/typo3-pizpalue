<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Functional\Rendering\ContentElement;

use Buepro\Pizpalue\Tests\Functional\FunctionalFrontendTestCase;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;

class TexticonRenderingTest extends FunctionalFrontendTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/RootPage.csv');
        $this->importCSVDataSet(__DIR__ . '/Fixtures/TexticonRenderingTest.csv');
        $this->setupFrontendSite(1);
    }

    public function checkIfRenderedIconHtmlContainsInlineStyleDataProvider(): array
    {
        return [
            'no color definitions' => ['circle', '', '', '#class="texticon-inner-icon">#'],
            'text color' => ['default', 'foo', '', '#class="texticon-inner-icon" style="color: foo;">#'],
            'background color' => ['circle', '', 'bar', '#class="texticon-inner-icon" style="background-color: bar;">#'],
            'text and background color' => ['circle', 'foo', 'bar', '#class="texticon-inner-icon" style="color: foo;background-color: bar;">#'],
        ];
    }

    /**
     * @test
     * @dataProvider checkIfRenderedIconHtmlContainsInlineStyleDataProvider
     */
    public function checkIfRenderedIconHtmlContainsInlineStyle(
        string $iconType,
        string $iconColor,
        string $iconBackground,
        string $expectedPattern
    ): void {
        // Disable test for TYPO3 10 due to access violation
        if (VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getNumericTypo3Version()) < 11000000) {
            return;
        }
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tt_content')
            ->update(
                'tt_content',
                [
                    'icon_type' => $iconType,
                    'icon_color' => $iconColor,
                    'icon_background' => $iconBackground,
                ],
                ['uid' => 1],
                [Connection::PARAM_STR, Connection::PARAM_STR]
            );
        $body = (string)$this->executeFrontendSubRequest((new InternalRequest())
            ->withQueryParameter('id', 1))
            ->getBody();
        self::assertMatchesRegularExpression($expectedPattern, $body);
    }
}
