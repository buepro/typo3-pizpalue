<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Unit\Structure\Service;

use Buepro\Pizpalue\Structure\Service\TypoScriptService;
use Buepro\Pizpalue\Tests\Unit\Structure\TypoScriptBasedTestCase;
use TYPO3\CMS\Core\TypoScript\TypoScriptService as CoreTypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TypoScriptServiceTest extends TypoScriptBasedTestCase
{
    public function testGetVariants(): void
    {
        /** @var CoreTypoScriptService $typoScriptService */
        $typoScriptService = GeneralUtility::makeInstance(CoreTypoScriptService::class);
        $setup = $typoScriptService->convertTypoScriptArrayToPlainArray($this->typoScriptSetupArray);
        $responsiveimagesSetup = $setup['lib']['contentElement']['settings']['responsiveimages'];
        $typoScriptService = new TypoScriptService();
        self::assertSame($responsiveimagesSetup['variants'], $typoScriptService->getVariants('variants'));
        self::assertSame($responsiveimagesSetup['pageVariants'], $typoScriptService->getVariants('pageVariants'));
        self::assertSame(
            $responsiveimagesSetup['contentelements']['pp_modal_dialog']['md'],
            $typoScriptService->getVariants('lib.contentElement.settings.responsiveimages.contentelements.pp_modal_dialog.md')
        );
        self::assertNull($typoScriptService->getVariants(''));
        self::assertNull($typoScriptService->getVariants('this.doesnt.exist'));
        self::assertNull($typoScriptService->getVariants('lib.this.doesnt.exist'));
    }
}
