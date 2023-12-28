<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Unit\Structure;

use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\TypoScript\AST\Node\RootNode;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

abstract class TypoScriptBasedTestCase extends UnitTestCase
{
    use ProphecyTrait;

    protected array $typoScriptSetupArray = [
        'lib.' => [
            'contentElement.' => [
                'settings.' => [
                    'responsiveimages.' => [
                        'variants.' => [
                            'default.' => [
                                'breakpoint' => 1400,
                                'width' => 1280,
                            ],
                            'xlarge.' => [
                                'breakpoint' => 1200,
                                'width' => 1100,
                            ],
                            'large.' => [
                                'breakpoint' => 992,
                                'width' => 920,
                            ],
                            'medium.' => [
                                'breakpoint' => 768,
                                'width' => 680,
                            ],
                            'small.' => [
                                'breakpoint' => 576,
                                'width' => 500,
                            ],
                            'extrasmall.' => [
                                'breakpoint' => 'unset',
                                'width' => 500,
                            ],
                        ],
                        'pageVariants.' => [
                            'default.' => [
                                'breakpoint' => 1400,
                                'width' => 2000,
                            ],
                            'xlarge.' => [
                                'breakpoint' => 1200,
                                'width' => 1400,
                            ],
                            'large.' => [
                                'breakpoint' => 992,
                                'width' => 1200,
                            ],
                            'medium.' => [
                                'breakpoint' => 768,
                                'width' => 992,
                            ],
                            'small.' => [
                                'breakpoint' => 576,
                                'width' => 768,
                            ],
                            'extrasmall.' => [
                                'breakpoint' => 'unset',
                                'width' => 576,
                            ],
                        ],
                        'backendlayout.' => [
                            'subnavigation_left.' => [
                                '0.' => [
                                    'margins.' => [
                                        'default' => -40,
                                        'xlarge' => -40,
                                        'large' => -40,
                                    ],
                                    'multiplier.' => [
                                        'default' => 0.75,
                                        'xlarge' => 0.75,
                                        'large' => 0.75,
                                    ],
                                    'corrections.' => [
                                        'default' => 40,
                                        'xlarge' => 40,
                                        'large' => 40,
                                    ],
                                ],
                                '1.' => [
                                    'margins.' => [
                                        'default' => -40,
                                        'xlarge' => -40,
                                        'large' => -40,
                                    ],
                                    'multiplier.' => [
                                        'default' => 0.25,
                                        'xlarge' => 0.25,
                                        'large' => 0.25,
                                    ],
                                    'corrections.' => [
                                        'default' => 40,
                                        'xlarge' => 40,
                                        'large' => 40,
                                    ],
                                ],
                            ],
                        ],
                        'contentelements.' => [
                            'pp_modal_dialog.' => [
                                'md.' => [
                                    'default.' => [
                                        'breakpoint' => 1200,
                                        'width' => 500,
                                    ],
                                    'large.' => [
                                        'breakpoint' => 992,
                                        'width' => 500,
                                    ],
                                    'medium.' => [
                                        'breakpoint' => 768,
                                        'width' => 500,
                                    ],
                                    'small.' => [
                                        'breakpoint' => 576,
                                        'width' => 500,
                                    ],
                                    'extrasmall.' => [
                                        'breakpoint' => 'unset',
                                        'width' => 542,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    public function setUp(): void
    {
        parent::setUp();
        $frontendTypoScript = new FrontendTypoScript(new RootNode(), []);
        $frontendTypoScript->setSetupArray($this->typoScriptSetupArray);
        $serverRequest = (new ServerRequest())->withAttribute('frontend.typoscript', $frontendTypoScript);
        $GLOBALS['TYPO3_REQUEST'] = $serverRequest;
    }
}
