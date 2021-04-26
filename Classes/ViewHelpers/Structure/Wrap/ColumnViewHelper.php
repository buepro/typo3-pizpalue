<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Structure\Wrap;

use Buepro\Pizpalue\Domain\Model\VariantsModifier;
use Buepro\Pizpalue\Utility\StructureVariantsUtility;
use Buepro\Pizpalue\Utility\StructureUtility;
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
    public function initializeArguments()
    {
        $this->registerArgument('class', 'string', 'CSS classes used to define the column', false, '');
        $this->registerArgument('count', 'int', 'Column count in row', false, 1);
        $this->registerArgument('gutter', 'float|array', 'Space between columns. In case a float is provided it will be used for all screen breakpoints.', false, 0);
        $this->registerArgument('correction', 'float|array', 'Correction to be subtracted. In case a float is provided it will be used for all screen breakpoints.', false, 0);
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
            $multiplier = StructureUtility::getMultiplierForColumn(
                $arguments['class'],
                $arguments['count']
            );

            // Push variants modifier -> render content -> pop modifier
            $modifier = GeneralUtility::makeInstance(VariantsModifier::class)
                ->setMultiplier($multiplier)
                ->setGutter($arguments['gutter'])
                ->setCorrection($arguments['correction']);
            StructureVariantsUtility::pushVariantsModifier($modifier);
            $content = $renderChildrenClosure();
            StructureVariantsUtility::popVariantsModifier();
            return $content;
        } else {
            return $renderChildrenClosure();
        }
    }
}
