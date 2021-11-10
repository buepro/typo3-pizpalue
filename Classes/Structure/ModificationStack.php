<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Structure;

/**
 * Mainly used for debugging purposes
 *
 * @see VariantsModifierStack::getVariants()
 * @see ModificationStackViewHelper
 */
class ModificationStack
{
    /**
     * @var Modification[]
     */
    private static $stack = [];

    public static function reset(): void
    {
        self::$stack = [];
    }

    public static function addModification(Modification $modification): Modification
    {
        self::$stack[] = $modification;
        return $modification;
    }

    public static function getStack(): array
    {
        return self::$stack;
    }
}
