<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\Tests\Unit\Utility;

use Buepro\Pizpalue\Easyconf\Utility\AppIconUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class AppIconUtilityTest extends UnitTestCase
{
    /**
     * @test
     */
    public function getHtmlWithLineBreaksContainsCRLFOnly(): void
    {
        $htmlWithoutLineBreaks = '<link href="/foo"><meta name="bar"><meta name="baz">';
        self::assertSame(
            '<link href="/foo">' . CRLF . '<meta name="bar">' . CRLF . '<meta name="baz">',
            AppIconUtility::getHtmlWithLineBreaks($htmlWithoutLineBreaks)
        );
    }

    /**
     * @test
     */
    public function getHtmlWithoutLineBreaksDropsAllLineFeeds(): void
    {
        $htmlWithLineBreaks = '<link href="/foo">' . CR . '<meta name="bar">' . LF . '<meta name="baz">' .
            CRLF . '<meta name="taz">';
        self::assertSame(
            '<link href="/foo"><meta name="bar"><meta name="baz"><meta name="taz">',
            AppIconUtility::getHtmlWithoutLineBreaks($htmlWithLineBreaks)
        );
    }
}
