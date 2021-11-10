<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Tests\Unit\Structure;

use Buepro\Pizpalue\Structure\TypoScript;

class TypoScriptTest extends TypoScriptBasedTest
{
    public function testGetVariants(): void
    {
        $ts = $this->typoScript['lib']['contentElement']['settings']['responsiveimages'];
        self::assertSame($ts['variants'], TypoScript::getVariants('variants'));
        self::assertSame($ts['pageVariants'], TypoScript::getVariants('pageVariants'));
        self::assertSame(
            $ts['contentelements']['pp_modal_dialog']['md'],
            TypoScript::getVariants('lib.contentElement.settings.responsiveimages.contentelements.pp_modal_dialog.md')
        );
        self::assertNull(TypoScript::getVariants(''));
        self::assertNull(TypoScript::getVariants('this.doesnt.exist'));
        self::assertNull(TypoScript::getVariants('lib.this.doesnt.exist'));
    }
}
