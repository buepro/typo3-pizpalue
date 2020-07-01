<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Structure\Render;

use Buepro\Pizpalue\Utility\StructureMultiplierUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * By wrapping columns with this view helper images can be rendered with the optimal size. This is achieved by
 * calculating a multiplier and pushing it to a stack. The latest multiplier is stored to the register
 * `structureMultiplier` from where it is available to all elements rendering images (e.g. see
 * `Resources\Private\Layouts\ContentElements\Default.html`).
 *
 * ### Example:
 *
 * <pp.structure.wrap.column class="col col-md-8 col-xl-6" count="2">
 *     <div class="col col-md-8 col-xl-6">
 *         <v:content.render contentUids="{0: item.data.uid}" />
 *     </div>
 * </pp.structure.render.column>
 */
class ColumnViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var array
     */
    private static $multiplierStack = [];

    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('class', 'string', 'CSS classes used to define the column', false, '');
        $this->registerArgument('count', 'int', 'Column count in row', false, 1);
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
        if ($GLOBALS['TSFE'] instanceof TypoScriptFrontendController) {
            // Initialize
            $currentMultiplier = self::getCurrentMultiplier();

            // Get multiplier for current structure
            $multiplier = StructureMultiplierUtility::getMultiplierForColumn(
                $currentMultiplier,
                $arguments['class'],
                $arguments['count']
            );

            // Push multiplier -> render content -> pop multiplier
            self::$multiplierStack[] = $multiplier;
            $GLOBALS['TSFE']->register['structureMultiplier'] = $multiplier;
            $content = $renderChildrenClosure();
            array_pop(self::$multiplierStack);
            $GLOBALS['TSFE']->register['structureMultiplier'] = self::getCurrentMultiplier();
            return $content;
        } else {
            return $renderChildrenClosure();
        }
    }

    private static function getCurrentMultiplier()
    {
        $currentMultiplier = [];
        if (count(self::$multiplierStack)) {
            $currentMultiplier = array_pop(self::$multiplierStack);
            self::$multiplierStack[] = $currentMultiplier;
        }
        return $currentMultiplier;
    }
}
