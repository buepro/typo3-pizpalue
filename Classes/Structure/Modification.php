<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Structure;

use Buepro\Pizpalue\Utility\StructureVariantsUtility;

class Modification
{
    /**
     * @var array
     */
    protected $initialVariants = [];

    /**
     * @var array
     */
    protected $resultingVariants = [];

    /**
     * @var VariantsModifier null
     */
    protected $variantsModifier = null;

    public function __construct(array $initialVariants, VariantsModifier $variantsModifier)
    {
        $this->initialVariants = $initialVariants;
        $this->variantsModifier = $variantsModifier;
        $this->resultingVariants = StructureVariantsUtility::getStructureVariants(
            $this->initialVariants,
            $this->variantsModifier->getMargins(),
            $this->variantsModifier->getGutters(),
            $this->variantsModifier->getMultiplier(),
            $this->variantsModifier->getCorrections()
        );
    }

    public function getInitialVariants(): array
    {
        return $this->initialVariants;
    }

    public function getResultingVariants(): array
    {
        return $this->resultingVariants;
    }

    public function getVariantsModifier(): VariantsModifier
    {
        return $this->variantsModifier;
    }
}
