<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Render\Bootstrap;

use Buepro\Pizpalue\Structure\VariantsModifier;
use Buepro\Pizpalue\Structure\VariantsModifierStack;
use Buepro\Pizpalue\Utility\ColumnVariantsUtility;
use Buepro\Pizpalue\Utility\StructureVariantsUtility;
use Buepro\Pizpalue\Utility\VectorUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * View helper to create a bootstrap column for optimized image rendering.
 * It is achieved by adding a variants modifier to the variants modifier stack.
 * See `Resources\Private\Layouts\ContentElements\Default.html`.
 *
 * ### Example:
 *
 * <pp:render.bootstrap.column class="col col-md-8 col-xl-6" count="2">
 *     <v:content.render contentUids="{0: item.data.uid}" />
 * </pp:render.bootstrap.column>
 *
 * @see VariantsModifierStack
 */
class ColumnViewHelper extends AbstractTagBasedViewHelper
{
    protected $tagName = 'div';

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerTagAttribute('itemscope', 'string', 'Itemscope attribute for this element');
        $this->registerTagAttribute('itemtype', 'string', 'Itemtype attribute for this element');
        $this->registerTagAttribute('role', 'string', 'Role attribute for this element');
        $this->registerArgument('rowClass', 'string', 'Classes assigned to the wrapping row', false, '');
        $this->registerArgument('count', 'int', 'Column count in row. Might be overwritten by rowClass definitions.', false, 1);
        $this->registerArgument('gutter', 'float|array', 'Space between columns. In case a number is provided it will be used for all screen breakpoints.', false, 0);
        $this->registerArgument('correction', 'float|array', 'Correction to be subtracted. In case a float is provided it will be used for all screen breakpoints.', false, 0);
        $this->registerArgument('tagName', 'string', 'Tag name', false, 'div');
    }

    public function render()
    {
        $this->tagName = $this->arguments['tagName'];
        $this->tag->setTagName($this->arguments['tagName']);
        $gutter = StructureVariantsUtility::getVectorProperty($this->arguments['gutter']);
        $multiplier = ColumnVariantsUtility::getMultiplier($this->arguments['class'], $this->arguments['rowClass'], $this->arguments['count']);
        $correction = StructureVariantsUtility::getVectorProperty($this->arguments['correction']);
        $modifier = (new VariantsModifier())
            ->setMargins(VectorUtility::negate($gutter))
            ->setMultiplier($multiplier)
            ->setCorrections(VectorUtility::addVector($gutter, $correction));

        // Push variants modifier -> render content -> pop modifier
        $variantsModifierStack = GeneralUtility::makeInstance(VariantsModifierStack::class);
        $variantsModifierStack->pushVariantsModifier($modifier);
        $this->tag->setContent(is_string($childrenContent = $this->renderChildren()) ? $childrenContent : '');
        $content = $this->tag->render();
        $variantsModifierStack->popVariantsModifier();
        return $content;
    }
}
