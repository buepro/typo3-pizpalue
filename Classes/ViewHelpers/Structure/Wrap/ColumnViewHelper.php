<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Structure\Wrap;

use Buepro\Pizpalue\Structure\VariantsModifier;
use Buepro\Pizpalue\Structure\VariantsModifierStack;
use Buepro\Pizpalue\Utility\ColumnVariantsUtility;
use Buepro\Pizpalue\Utility\StructureVariantsUtility;
use Buepro\Pizpalue\Utility\VectorUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
 * </pp.structure.wrap.column>
 */
class ColumnViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

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
    public function initializeArguments(): void
    {
        $this->registerArgument('class', 'string', 'CSS classes used to define the column', false, '');
        $this->registerArgument('rowClass', 'string', 'Classes assigned to the wrapping row.', false, '');
        $this->registerArgument('count', 'int', 'Column count in row', false, 1);
        $this->registerArgument('gutter', 'float|array', 'Space between columns. In case a number is provided it will be used for all screen breakpoints.', false, 0);
        $this->registerArgument('correction', 'float|array', 'Correction to be subtracted. In case a float is provided it will be used for all screen breakpoints.', false, 0);
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        if ($GLOBALS['TSFE'] instanceof TypoScriptFrontendController) {
            $gutter = StructureVariantsUtility::getVectorProperty($arguments['gutter']);
            $multiplier = ColumnVariantsUtility::getMultiplier($arguments['class'], $arguments['rowClass'], $arguments['count']);
            $correction = StructureVariantsUtility::getVectorProperty($arguments['correction']);
            $modifier = (new VariantsModifier())
                ->setMargins(VectorUtility::negate($gutter))
                ->setMultiplier($multiplier)
                ->setCorrections(VectorUtility::addVector($gutter, $correction));

            // Push variants modifier -> render content -> pop modifier
            $variantsModifierStack = GeneralUtility::makeInstance(VariantsModifierStack::class);
            $variantsModifierStack->pushVariantsModifier($modifier);
            $content = $renderChildrenClosure();
            $variantsModifierStack->popVariantsModifier();
            return $content;
        }

        return $renderChildrenClosure();
    }
}
