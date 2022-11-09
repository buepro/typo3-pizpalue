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

class IconGroupRenderingTest extends FunctionalFrontendTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/RootPage.csv');
        $this->importCSVDataSet(__DIR__ . '/Fixtures/IconGroupRenderingTest.csv');
        $this->setupFrontendSite(1);
    }

    public function checkIfIconHtmlContainsCorrectClassesDataProvider(): array
    {
        return [
            'no color' => ['', '', '#class="icongroup-item-icon"#'],
            'option color' => ['foo', '', '#class="icongroup-item-icon foo"#'],
            'item color' => ['', 'bar', '#class="icongroup-item-icon bar"#'],
            'option and item color' => ['foo', 'bar', '#class="icongroup-item-icon bar"#'],
        ];
    }

    /**
     * @test
     * @dataProvider checkIfIconHtmlContainsCorrectClassesDataProvider
     */
    public function checkIfIconHtmlContainsCorrectClasses(
        string $optionsIconColor,
        string $iconColor,
        string $expectedPattern
    ): void {
        // Disable test for TYPO3 10 due to access violation
        if (VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getNumericTypo3Version()) < 11000000) {
            return;
        }
        $options = sprintf('<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<T3FlexForms>
    <data>
        <sheet index="sDEF">
            <language index="lDEF">
                <field index="align">
                    <value index="vDEF">center</value>
                </field>
                <field index="columns">
                    <value index="vDEF">2</value>
                </field>
                <field index="icon_position">
                    <value index="vDEF">left-center</value>
                </field>
                <field index="icon_color">
                    <value index="vDEF">%s</value>
                </field>
            </language>
        </sheet>
    </data>
</T3FlexForms>', $optionsIconColor);
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tt_content')
            ->update(
                'tt_content',
                ['pi_flexform' => $options],
                ['uid' => 1],
                [Connection::PARAM_STR]
            );
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_bootstrappackage_icon_group_item')
            ->update(
                'tx_bootstrappackage_icon_group_item',
                ['tx_pizpalue_icon_color' => $iconColor],
                ['uid' => 1],
                [Connection::PARAM_STR]
            );
        $body = (string)$this->executeFrontendSubRequest((new InternalRequest())
            ->withQueryParameter('id', 1))
            ->getBody();
        self::assertMatchesRegularExpression($expectedPattern, $body);
    }
}
