<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Structure\Multiplier;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class GetFromTextViewHelper
 */
class GetFromTextViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    protected static $defaultMultipliers = [
        'default' => 1,
        'large' => 1,
        'medium' => 1,
        'small' => 1,
        'extrasmall' => 1
    ];

    protected static $breakpointMap = [
        'xl' => 'default',
        'lg' => 'large',
        'md' => 'medium',
        'sm' => 'small',
        'xs' => 'extrasmall'
    ];

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('as', 'string', 'Name of variable to create.', true);
        $this->registerArgument('text', 'string', 'Comma separated list from screen breakpoint multipliers', true);
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
        $multipliers = self::$defaultMultipliers;
        $lines = GeneralUtility::trimExplode(',', $arguments['text'], true);
        foreach ($lines as $key => $line) {
            $parts = GeneralUtility::trimExplode(':', $line, true);
            if (count($parts) === 2) {
                if (array_key_exists($parts[0], $multipliers)) {
                    $multipliers[$parts[0]] = (float) $parts[1];
                }
                if (array_key_exists($parts[0], self::$breakpointMap)) {
                    $multipliers[self::$breakpointMap[$parts[0]]] = (float) $parts[1];
                }
            }
        }
        $renderingContext->getVariableProvider()->add($arguments['as'], $multipliers);
    }
}
