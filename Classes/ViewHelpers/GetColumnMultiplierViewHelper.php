<?php


namespace Buepro\Pizpalue\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Used to create a multiplier array used for creating image variants.
 * A start multiplier can be provided which is useful when being used in cascading structures (e.g. column in column).
 *
 * Currently just supports css from bootstrap framework.
 *
 * Usage:
 * {pp:getColumnMultiplier(as: 'multiplier', multiplier: multiplier, css: 'col-sm col-md-8 col-lg-10', count: 3)}
 */
class GetColumnMultiplierViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    protected static $defaultMultiplier = [
        'extrasmall' => 1,
        'small' => 1,
        'medium' => 1,
        'large' => 1,
        'default' => 1
    ];

    protected static $breakpointMap = [
        'extrasmall' => '',
        'small' => 'sm',
        'medium' => 'md',
        'large' => 'lg',
        'default' => 'xl'
    ];

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('as', 'string', 'Name of variable to create.', true);
        $this->registerArgument('multiplier', 'array', 'Initial multiplier', false);
        $this->registerArgument('css', 'string', 'Css used for defining the column', false);
        $this->registerArgument('count', 'int', 'Column count in row', false);

    }

    /**
     * @param array $items
     * @param int $count
     * @param string $breakpoint
     * @return bool|float|int
     */
    private static function getFactorForBreakpoint(array $items, int $count, string $breakpoint) {
        $factor = false;
        foreach ($items as $item) {
            $parts = GeneralUtility::trimExplode('-', $item, true);
            if ($parts[0] !== 'col') {
                continue;
            }
            // CSS definition `col`
            if (count($parts) === 1 && $breakpoint === 'extrasmall') {
                $factor = 1 / $count;
            }
            // CSS definition `col-3`
            if (count($parts) === 2 && (int) $parts[1] > 0 && $breakpoint === 'extrasmall') {
                $factor = ((int) $parts[1]) / 12;
            }
            // CSS definition `col-md`
            if (count($parts) === 2 && $parts[1] === self::$breakpointMap[$breakpoint]) {
                $factor = 1 / $count;
            }
            // CSS definition `col-md-3`
            if (count($parts) === 3 && $parts[1] === self::$breakpointMap[$breakpoint]) {
                $factor = ((int) $parts[2]) / 12;
            }
        }
        return $factor;
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
        // Set initial multiplier
        $multiplier = self::$defaultMultiplier;
        if (isset($arguments['multiplier']) && is_array($arguments['multiplier'])) {
            $multiplier = array_merge($multiplier, $arguments['multiplier']);
            $multiplier = array_intersect_key($multiplier, self::$defaultMultiplier);
        }
        // Set column count
        $count = 1;
        if (isset($arguments['count']) && (int) $arguments['count'] > 1) {
            $count = (int) $arguments['count'];
        }
        // Calculate new multipliers
        $items = GeneralUtility::trimExplode(' ', $arguments['css'], true);
        $previousFactor = 1.0;
        foreach ($multiplier as $breakpoint => $value) {
            $factor = self::getFactorForBreakpoint($items, $count, $breakpoint);
            if (false === $factor) {
                // No column definition was present hence column width remains as for previous break point
                $multiplier[$breakpoint] = $previousFactor * $value;
            } else {
                $multiplier[$breakpoint] = $factor * $value;
                $previousFactor = $factor;
            }
        }
        $renderingContext->getVariableProvider()->add($arguments['as'], $multiplier);
    }

}
