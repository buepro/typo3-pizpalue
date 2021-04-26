<?php

namespace Buepro\Pizpalue\Domain\Model;

use BK2K\BootstrapPackage\Utility\ImageVariantsUtility;

class VariantsModifier
{
    /**
     * multiplier
     *
     * @var array
     */
    protected $multiplier = [];

    /**
     * gutter
     *
     * @var array
     */
    protected $gutter = [];

    /**
     * correction
     *
     * @var array
     */
    protected $correction = [];

    /**
     * Variants to replace preceding variants modifications. This might be needed in cases where content elements
     * are rendered in a new context (e.g. A modal dialog shown from within columns. In this case the column from
     * which the modal dialog is activated has a modified variants that need to be overwritten).
     *
     * @var array|null
     */
    protected $variants = null;

    /**
     * defaultVariants
     *
     * @var array
     */
    private $defaultVariants;

    public function __construct()
    {
        $this->defaultVariants = ImageVariantsUtility::getImageVariants();
    }

    /**
     * Ensures the modifier is consistent. In case a scalar is passed in it will be assigned as a float to all screen
     * breakpoints. In case of an array just the valid screen breakpoint elements will be used.
     *
     * @param $modifier
     * @return array
     */
    private function validateModifier($modifier): array
    {
        $validatedModifier = [];
        if (is_array($modifier)) {
            foreach($this->defaultVariants as $breakpoint => $value) {
                if (isset($modifier[$breakpoint])) {
                    $validatedModifier[$breakpoint] = (float) $modifier[$breakpoint];
                }
            }
        } else {
            $validatedModifier = array_fill_keys(array_keys($this->defaultVariants), (float) $modifier);
        }
        return $validatedModifier;
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
     * @param $multiplier
     * @return VariantsModifier
     */
    public function setMultiplier($multiplier): VariantsModifier
    {
        $this->multiplier = $this->validateModifier($multiplier);
        return $this;
    }

    /**
     * Returns the gutter
     *
     * @return array
     */
    public function getGutter(): array
    {
        return $this->gutter;
    }

    /**
     * Sets the gutter
     *
     * @param $gutter
     * @return VariantsModifier
     */
    public function setGutter($gutter): VariantsModifier
    {
        $this->gutter = $this->validateModifier($gutter);
        return $this;
    }

    /**
     * Returns the correction
     *
     * @return array
     */
    public function getCorrection(): array
    {
        return $this->correction;
    }

    /**
     * Sets the correction
     *
     * @param $correction
     * @return VariantsModifier
     */
    public function setCorrection($correction): VariantsModifier
    {
        $this->correction = $this->validateModifier($correction);
        return $this;
    }

    /**
     * Returns the variants
     *
     * @return array|null
     */
    public function getVariants(): ?array
    {
        return $this->variants;
    }

    /**
     * Sets the variants
     *
     * @param $variants
     * @return VariantsModifier
     */
    public function setVariants($variants): VariantsModifier
    {
        $this->variants = ImageVariantsUtility::getImageVariants($variants);
        return $this;
    }
}
