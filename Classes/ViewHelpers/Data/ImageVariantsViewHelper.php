<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Data;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class ImageVariantsViewHelper
 *
 * Allows to modify the aspect ratio from an image variants.
 */
class ImageVariantsViewHelper extends AbstractViewHelper
{
    /**
     * @var string[]
     */
    protected static $breakpoints = ['default', 'xlarge', 'large', 'medium', 'small', 'extrasmall'];

    /**
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('as', 'string', 'Name of variable to create.', true);
        $this->registerArgument('variants', 'array', 'Variants for responsive images to be modified.', false);
        $this->registerArgument('aspectRatio', 'array', 'Aspect ratios for screen breakpoint.', true);
    }

    /**
     * @return mixed
     */
    public function render()
    {
        $result = $this->arguments['variants'];
        foreach (self::$breakpoints as $breakpoint) {
            if (isset($this->arguments['aspectRatio'][$breakpoint]) && (float) $this->arguments['aspectRatio'][$breakpoint] > 0) {
                $result[$breakpoint]['aspectRatio'] = (float) $this->arguments['aspectRatio'][$breakpoint];
            }
        }
        if (isset($this->arguments['as']) && $this->arguments['as'] !== '') {
            $this->renderingContext->getVariableProvider()->add($this->arguments['as'], $result);
            return '';
        }
        return $result;
    }
}
