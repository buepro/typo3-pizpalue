<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Structure\Multiplier;

use Buepro\Pizpalue\Utility\ColumnVariantsUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Used to generate a multiplier array used for creating image variants.
 * A start multiplier can be provided which is useful when being used in nested structures (e.g. column in column).
 *
 * Currently just supports css from bootstrap framework.
 *
 * Usage:
 * {pp:structure.multiplier.getForColumn(as: 'multiplier', multiplier: multiplier, class: 'col-sm col-md-8 col-lg-10', count: 3)}
 *
 */
class GetForColumnViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('as', 'string', 'Name of variable to create.', true);
        $this->registerArgument('multiplier', 'array', 'Initial multiplier', false);
        $this->registerArgument('class', 'string', 'CSS classes used for defining the column', false);
        $this->registerArgument('rowClass', 'string', 'Classes assigned to the wrapping row.', false, '');
        $this->registerArgument('count', 'int', 'Column count in row. Might be overwritten by rowClass definitions.', false);
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
        $multiplier = $arguments['multiplier'] ?? [];
        $class = $arguments['class'] ?? '';
        $rowClass = $arguments['rowClass'] ?? '';
        $count = $arguments['count'] ?? 1;
        $multiplier = ColumnVariantsUtility::getMultiplier($class, $rowClass, $count, $multiplier);
        if ($arguments['as']) {
            $renderingContext->getVariableProvider()->add($arguments['as'], $multiplier);
            return '';
        }
        return $multiplier;
    }
}
