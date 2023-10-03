<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Functional\DataProcessing;

use Buepro\Pizpalue\DataProcessing\TextReplacementProcessor;
use Buepro\Pizpalue\Tests\Functional\FunctionalFrontendTestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class TextReplacementProcessorTest extends FunctionalFrontendTestCase
{
    use ProphecyTrait;

    protected const PAGE_UID = 1;
    protected const CONTENT_UID = 2;

    /**
     * @var non-empty-string[]
     */
    protected array $coreExtensionsToLoad = [
        'impexp',
        'seo',
    ];

    protected Connection $dbConnection;

    protected array $pageData = [
        'title' => 'Pizpalue title',
        'seo_title' => 'SEO title goes here',
        'description' => 'SEO description goes here'
    ];

    /**
     * @var string[] $contentData
     */
    protected array $contentData = [
        'bodytext' =>
'{
  "@context": "http://schema.org",
  "@graph": [
    {
      "@type" : "Organization",
      "name": "{$pizpalue.customer.company}",
      "email": "{func:entityEncodeChars:{$pizpalue.customer.contactEmail}}",
      "telephone": "{$pizpalue.customer.contactPhone}",
      "url" : "https://www.{$pizpalue.customer.domain}",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "{$pizpalue.customer.contactCity}, Switzerland",
        "postalCode": "CH-{$pizpalue.customer.contactZip}",
        "streetAddress": "{$pizpalue.customer.contactAddress}"
      },
      "contactPoint" : [
        { "@type" : "ContactPoint",
          "telephone" : "{$pizpalue.customer.contactPhone}",
          "contactType" : "customer service"
        }
      ]
    },
    {
      "@type":"WebPage",
      "headline":"{parentData:field:seo_title // field:title}",
      "description":"{func:newLineToRn:{parentData:field:description}}",
      "breadcrumb":{processedData:breadcrumb}
    }
  ]
}',
    ];

    protected string $defaultExpected =
'{
  "@context": "http://schema.org",
  "@graph": [
    {
      "@type" : "Organization",
      "name": "Company GmbH",
      "email": "&#105;&#110;&#102;&#111;&#64;&#100;&#111;&#109;&#97;&#105;&#110;&#46;&#99;&#104;",
      "telephone": "+41 11 111 11 11",
      "url" : "https://www.domain.ch",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "City, Switzerland",
        "postalCode": "CH-1000",
        "streetAddress": "Street 1"
      },
      "contactPoint" : [
        { "@type" : "ContactPoint",
          "telephone" : "+41 11 111 11 11",
          "contactType" : "customer service"
        }
      ]
    },
    {
      "@type":"WebPage",
      "headline":"SEO title goes here",
      "description":"SEO description goes here",
      "breadcrumb":{processedData:breadcrumb}
    }
  ]
}';

    /**
     * Sets up this test case.
     */
    protected function setUp(): void
    {
        parent::setUp();
        foreach (['pages', 'sys_template', 'tt_content'] as $table) {
            $this->importCSVDataSet(sprintf(
                '%s/db_table_%s.csv',
                __DIR__ . '/../Fixtures',
                $table
            ));
        }

        $this->setupFrontendSite(1);
        $this->dbConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('pages');
    }

    protected function setupFrontendController(int $pageUid = 1, array $pageData = [], array $contentData = []): void
    {
        $this->dbConnection->update('pages', $pageData, ['uid' => self::PAGE_UID]);
        $this->dbConnection->update('tt_content', $contentData, ['uid' => self::CONTENT_UID]);
        parent::setupFrontendController($pageUid);
    }

    protected function getContentRecord(int $uid = self::CONTENT_UID): array
    {
        /** @phpstan-ignore-next-line */
        return $this->dbConnection
            ->select(['*'], 'tt_content', ['uid' => $uid])
            ->fetchAssociative();
    }

    public function processDataProvider(): array
    {
        $pageData = $this->pageData;
        $pageData['seo_title'] = '';
        $expected = str_replace(
            '"headline":"SEO title goes here",',
            '"headline":"Pizpalue title",',
            $this->defaultExpected
        );
        return [
            'default' => [$this->pageData, $this->contentData, $this->defaultExpected],
            'option split' => [$pageData, $this->contentData, $expected],
        ];
    }

    /**
     * @dataProvider processDataProvider
     * @test
     */
    public function process(array $pageData, array $contentData, string $expected): void
    {
        $this->setupFrontendController(self::PAGE_UID, $pageData, $contentData);
        $contentData = $this->getContentRecord();
        $cObj = new ContentObjectRenderer($GLOBALS['TSFE']);
        $cObj->start($contentData, 'tt_content');
        $processor = new TextReplacementProcessor();
        ['data' => ['bodytext' => $actual]] = $processor->process(
            $cObj,
            [],
            [
                'references.' => [
                    'fieldName' => 'bodytext',
                ],
            ],
            ['data' => ['bodytext' => $contentData['bodytext']]]
        );
        self::assertSame($expected, $actual);
    }
}
