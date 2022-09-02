<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Unit\Utility;

use Buepro\Pizpalue\Utility\TcaUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class TcaUtilityTest extends UnitTestCase
{
    /**
     * @var string[]
     */
    protected $breakpoints = [
        'default',
        'large',
        'medium',
        'small',
        'extrasmall'
    ];

    /**
     * @var array[]
     */
    protected $aspectRatios = [
        '2:1' => [
            'title' => '2:1',
            'value' => 2
        ],
        '16:9' => [
            'title' => '16:9',
            'value' => 16 / 9
        ],
        '4:3' => [
            'title' => '4:3',
            'value' => 4 / 3
        ],
        '1:1' => [
            'title' => '1:1',
            'value' => 1.0
        ],
        '3:4' => [
            'title' => '3:4',
            'value' => 3 / 4
        ],
        '9:16' => [
            'title' => '9:16',
            'value' => 9 / 16
        ],
        '1:2' => [
            'title' => '1:2',
            'value' => 0.5
        ],
        'NaN' => [
            'title' => 'Free',
            'value' => 0.0
        ],
    ];

    /**
     * @test
     */
    public function assignAllowedAspectRatiosToCropVariants(): void
    {
        $tca = ['some' => ['array' => []]];
        $actual = $tca;
        TcaUtility::assignAllowedAspectRatiosToCropVariants($actual['some']['array']);
        $expected = $tca;
        foreach ($this->breakpoints as $breakpoint) {
            $expected['some']['array'][$breakpoint]['allowedAspectRatios'] = $this->aspectRatios;
        }
        self::assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function setAllowedAspectRatiosForField(): void
    {
        $expected = [];
        foreach ($this->breakpoints as $breakpoint) {
            $expected[$breakpoint]['allowedAspectRatios'] = $this->aspectRatios;
        }
        TcaUtility::setAllowedAspectRatiosForField('test_table', 'test_field');
        self::assertSame(
            $expected,
            $GLOBALS['TCA']['test_table']['columns']
                ['test_field']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants']
        );
    }

    /**
     * @test
     */
    public function setAllowedAspectRatiosForCType(): void
    {
        $expected = [];
        foreach ($this->breakpoints as $breakpoint) {
            $expected[$breakpoint]['allowedAspectRatios'] = $this->aspectRatios;
        }
        TcaUtility::setAllowedAspectRatiosForCType('test_table', 'test_type', 'test_field');
        self::assertSame(
            $expected,
            $GLOBALS['TCA']['test_table']['types']['test_type']['columnsOverrides']
                ['test_field']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants']
        );
    }
}
