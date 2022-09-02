<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Structure;

use Buepro\Pizpalue\Utility\StructureVariantsUtility;

class VariantsModifier
{
    /**
     * margins
     *
     * @var array
     */
    protected $margins = [];

    /**
     * gutters
     *
     * @var array
     */
    protected $gutters = [];

    /**
     * multiplier
     *
     * @var array
     */
    protected $multiplier = [];

    /**
     * corrections
     *
     * @var array
     */
    protected $corrections = [];

    /**
     * Variants to replace preceding variants modifications. This might be needed in cases where content elements
     * are rendered in a new context (e.g. A modal dialog shown from within columns. In this case the column from
     * which the modal dialog is activated has a modified variants that need to be overwritten).
     *
     * @var array|null
     */
    protected $variants = null;

    /**
     * Ensures the modifier is consistent. In case a scalar is passed in it will be assigned as a float to all screen
     * breakpoints. In case of an array just the valid screen breakpoint elements will be used.
     *
     * @param int|float|array $modifier
     * @return float[]
     */
    private function validateModifier($modifier)
    {
        $defaultVariants = StructureVariantsUtility::getStructureVariants();
        $validatedModifier = [];
        if (is_array($modifier)) {
            foreach ($defaultVariants as $breakpoint => $value) {
                if (isset($modifier[$breakpoint])) {
                    $validatedModifier[$breakpoint] = (float) $modifier[$breakpoint];
                }
            }
        } else {
            $validatedModifier = array_fill_keys(array_keys($defaultVariants), (float)$modifier);
        }
        return $validatedModifier;
    }

    /**
     * Returns the margins
     */
    public function getMargins(): array
    {
        return $this->margins;
    }

    /**
     * Sets the margins
     *
     * @param float|array $margins
     */
    public function setMargins($margins): self
    {
        $this->margins = $this->validateModifier($margins);
        return $this;
    }

    /**
     * Returns the gutters
     */
    public function getGutters(): array
    {
        return $this->gutters;
    }

    /**
     * Sets the gutters
     *
     * @param float|array $gutters
     */
    public function setGutters($gutters): self
    {
        $this->gutters = $this->validateModifier($gutters);
        return $this;
    }

    /**
     * Returns the multiplier
     *
     * @return array
     */
    public function getMultiplier(): array
    {
        return $this->multiplier;
    }

    /**
     * Sets the multiplier
     *
     * @param float|array $multiplier
     */
    public function setMultiplier($multiplier): self
    {
        $this->multiplier = $this->validateModifier($multiplier);
        return $this;
    }

    /**
     * Returns the corrections
     *
     * @return array
     */
    public function getCorrections(): array
    {
        return $this->corrections;
    }

    /**
     * Sets the corrections
     *
     * @param float|array $corrections
     */
    public function setCorrections($corrections): self
    {
        $this->corrections = $this->validateModifier($corrections);
        return $this;
    }

    public function setModification(array $modification): self
    {
        if (isset($modification['margins'])) {
            $this->setMargins($modification['margins']);
        }
        if (isset($modification['gutters'])) {
            $this->setGutters($modification['gutters']);
        }
        if (isset($modification['multiplier'])) {
            $this->setMultiplier($modification['multiplier']);
        }
        if (isset($modification['corrections'])) {
            $this->setCorrections($modification['corrections']);
        }
        return $this;
    }

    /**
     * Returns the variants
     */
    public function getVariants(): ?array
    {
        return $this->variants;
    }

    /**
     * Sets the variants
     */
    public function setVariants(array $variants): self
    {
        $this->variants = StructureVariantsUtility::getStructureVariants($variants);
        return $this;
    }
}
