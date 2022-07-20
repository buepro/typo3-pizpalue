<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Structure;

use TYPO3\CMS\Core\SingletonInterface;

/**
 * Mainly used for debugging purposes
 *
 * @see VariantsModifierStack::getVariants()
 * @see ModificationStackViewHelper
 */
class ModificationStack implements SingletonInterface
{
    /**
     * @var Modification[]
     */
    private $stack = [];

    public function reset(): void
    {
        $this->stack = [];
    }

    public function addModification(Modification $modification): Modification
    {
        $this->stack[] = $modification;
        return $modification;
    }

    public function getStack(): array
    {
        return $this->stack;
    }
}
