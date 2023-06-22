<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Functional\Rendering\ContentElement;

use Buepro\Pizpalue\Structure\Service\TypoScriptService;
use Buepro\Pizpalue\Tests\Functional\FunctionalFrontendTestCase;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;

class PpModalDialogRenderingTest extends FunctionalFrontendTestCase
{
    /**
    * @var array<string, non-empty-string>
    */
    protected array $pathsToProvideInTestInstance = [
        'typo3conf/ext/pizpalue/Initialisation/Files/' => 'fileadmin/pizpalue/',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/RootPage.csv');
        $this->importCSVDataSet(__DIR__ . '/Fixtures/PpModalDialogRenderingTest.csv');
        $this->setupFrontendSite(1);
    }

    public function checkRenderedHtmlDataProvider(): array
    {
        return [
            'button and dialog' => [
                '', '', '', '', '', '',
                '/id="c1"[\s\S]*frame-inner">[\r\n\s]*<button[\s\S]*data-bs-target="#pp-modal-dialog-1"[\s\S]*class="modal [\s\S]*id="pp-modal-dialog-1/'
            ],
            'button text' => [
                'Show modal dialog', '', '', '', '', '',
                '@data-bs-target="#pp-modal-dialog-1"[\s\S\\]*Show modal dialog[\s\\n]*</button>@'
            ],
            'dialog header' => [
                '', 'Dialog header goes here', '', '', '', '',
                '@id="pp-modal-dialog-1"[\s\S]*class="modal-title"[\s\S]*>Dialog header goes here</@'
            ],
            'content' => [
                '', '', 'tt_content_2', '', '', '',
                '@id="c1"[\s\S]*class="modal-body">[\r\n\w\s]*<div id="c2"@'
            ],
            'url' => [
                '', '', '', 'https://example.com', '', '',
                '@pp-modal-dialog ppc-iframe[\s\S]*data-pp-src="https://example\.com"@'
            ],
            'button class' => [
                '', '', '', '', 'foo', '',
                '@<button[\s\S\\\]*class="foo"[\s\S\\\]*data-bs-target="#pp-modal-dialog-1"@'
            ],
            'dialog class' => [
                '', '', '', '', '', 'foo',
                '@id="pp-modal-dialog-1"[\s\S]*<div class="foo" role="document">[\r\n\s]*<div class="modal-content">@'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider checkRenderedHtmlDataProvider
     */
    public function checkRenderedHtml(
        string $buttonText,
        string $dialogHeader,
        string $records,
        string $url,
        string $buttonClass,
        string $dialogClass,
        string $expectedPattern
    ): void {
        // Disable test for TYPO3 10 due to access violation
        if (VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getNumericTypo3Version()) < 11000000) {
            return;
        }
        $body = $this->getFrontendHtml($buttonText, $dialogHeader, $records, $url, $buttonClass, $dialogClass);
        self::assertMatchesRegularExpression($expectedPattern, $body);
    }

    /** @test */
    public function checkImageSizes(): void
    {
        // Disable test for TYPO3 10 due to access violation
        if (VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getNumericTypo3Version()) < 11000000) {
            return;
        }
        GeneralUtility::rmdir('fileadmin/_processed_', true);
        $body = $this->getFrontendHtml(
            '',
            '',
            'tt_content_2',
            '',
            '',
            'modal-dialog modal-dialog-centered modal-dialog-scrollable'
        );
        $count = preg_match_all('@data-maxwidth="(\d*)"@', $body, $matches);
        self::assertSame(5, $count);
        $matches = $matches[1];
        $modalDialogVariants = (new TypoScriptService())->getVariants(
            'lib.contentElement.settings.responsiveimages.contentelements.pp_modal_dialog.default'
        );
        if ($modalDialogVariants === null) {
            throw new \LogicException('Modal dialog variants not defined.', 1661348490);
        }
        $matchesIndex = 0;
        foreach ($modalDialogVariants as $modalDialogVariant) {
            self::assertSame((int)$modalDialogVariant['width'], (int)$matches[$matchesIndex++]);
        }
    }

    private function getFrontendHtml(
        string $buttonText = '',
        string $dialogHeader = '',
        string $records = '',
        string $url = '',
        string $buttonClass = '',
        string $dialogClass = ''
    ): string {
        $options = sprintf(
            '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<T3FlexForms>
    <data>
        <sheet index="sDEF">
            <language index="lDEF">
                <field index="button_class">
                    <value index="vDEF">%s</value>
                </field>
                <field index="dialog_class">
                    <value index="vDEF">%s</value>
                </field>
            </language>
        </sheet>
    </data>
</T3FlexForms>',
            $buttonClass,
            $dialogClass
        );
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tt_content')
            ->update(
                'tt_content',
                [
                    'header' => $buttonText,
                    'subheader' => $dialogHeader,
                    'records' => $records,
                    'readmore_label' => $url,
                    'pi_flexform' => $options,
                ],
                ['uid' => 1],
                [Connection::PARAM_STR, Connection::PARAM_STR, Connection::PARAM_STR, Connection::PARAM_STR]
            );
        return (string)$this->executeFrontendSubRequest((new InternalRequest())
            ->withQueryParameter('id', 1))
            ->getBody();
    }
}
