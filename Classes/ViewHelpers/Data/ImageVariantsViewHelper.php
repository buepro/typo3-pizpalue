<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Data;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class ImageVariantsViewHelper
 *
 * Allows to modify the aspect ratio from an image variants.
 */
class ImageVariantsViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    protected static $breakpoints = ['default', 'xlarge', 'large', 'medium', 'small', 'extrasmall'];

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('as', 'string', 'Name of variable to create.', true);
        $this->registerArgument('variants', 'array', 'Variants for responsive images.', false);
        $this->registerArgument('aspectRatio', 'array', 'Aspect ratios for screen breakpoint.', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $result = $arguments['variants'];
        foreach (self::$breakpoints as $breakpoint) {
            if (isset($arguments['aspectRatio'][$breakpoint]) && (float) $arguments['aspectRatio'][$breakpoint] > 0) {
                $result[$breakpoint]['aspectRatio'] = (float) $arguments['aspectRatio'][$breakpoint];
            }
        }
        $renderingContext->getVariableProvider()->add($arguments['as'], $result);
    }
}
