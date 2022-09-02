<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Unit\Structure\Service;

use Buepro\Pizpalue\Structure\Service\TypoScriptService;
use Buepro\Pizpalue\Tests\Unit\Structure\TypoScriptBasedTest;

class TypoScriptServiceTest extends TypoScriptBasedTest
{
    public function testGetVariants(): void
    {
        $typoScriptService = new TypoScriptService();
        $ts = $this->typoScript['lib']['contentElement']['settings']['responsiveimages'];
        self::assertSame($ts['variants'], $typoScriptService->getVariants('variants'));
        self::assertSame($ts['pageVariants'], $typoScriptService->getVariants('pageVariants'));
        self::assertSame(
            $ts['contentelements']['pp_modal_dialog']['md'],
            $typoScriptService->getVariants('lib.contentElement.settings.responsiveimages.contentelements.pp_modal_dialog.md')
        );
        self::assertNull($typoScriptService->getVariants(''));
        self::assertNull($typoScriptService->getVariants('this.doesnt.exist'));
        self::assertNull($typoScriptService->getVariants('lib.this.doesnt.exist'));
    }
}
