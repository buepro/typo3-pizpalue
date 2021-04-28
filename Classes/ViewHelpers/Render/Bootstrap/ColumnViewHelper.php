<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Render\Bootstrap;

use Buepro\Pizpalue\Domain\Model\VariantsModifier;
use Buepro\Pizpalue\Utility\StructureUtility;
use Buepro\Pizpalue\Utility\StructureVariantsUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * By using this view helper to create a div-tag defining a bootstrap column the available width within
 * the column is being registered allowing to adjust the rendering. This allows images to be created with
 * the size that fits the column.
 *
 * It is achieved by calculating a multiplier and pushing it to a stack. The latest multiplier is stored to the register
 * `structureMultiplier` from where it is available to all elements rendering images (e.g. see
 * `Resources\Private\Layouts\ContentElements\Default.html`).
 *
 * ### Example:
 *
 * <pp:render.bootstrap.column class="col col-md-8 col-xl-6" count="2">
 *     <v:content.render contentUids="{0: item.data.uid}" />
 * </pp:render.bootstrap.column>
 */
class ColumnViewHelper extends AbstractTagBasedViewHelper
{
    protected $tagName = 'div';

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerTagAttribute('itemscope', 'string', 'Itemscope attribute');
        $this->registerTagAttribute('itemtype', 'string', 'Itemtype attribute');
        $this->registerArgument('count', 'int', 'Column count in row', false, 1);
        $this->registerArgument('gutter', 'float|array', 'Space between columns. In case a float is provided it will be used for all screen breakpoints.', false, 0);
        $this->registerArgument('correction', 'float|array', 'Correction to be subtracted. In case a float is provided it will be used for all screen breakpoints.', false, 0);
        $this->registerArgument('tagName', 'string', 'Tag name', false, 'div');
    }

    public function render()
    {
        $this->tagName = $this->arguments['tagName'];
        if ($GLOBALS['TSFE'] instanceof TypoScriptFrontendController) {
            $multiplier = StructureUtility::getMultiplierForColumn(
                $this->arguments['class'],
                $this->arguments['count']
            );

            // Push variants modifier -> render content -> pop modifier
            $modifier = GeneralUtility::makeInstance(VariantsModifier::class)
                ->setMultiplier($multiplier)
                ->setGutter($this->arguments['gutter'])
                ->setCorrection($this->arguments['correction']);
            StructureVariantsUtility::pushVariantsModifier($modifier);
            $this->tag->setContent($this->renderChildren());
            $content = $this->tag->render();
            StructureVariantsUtility::popVariantsModifier();
            return $content;
        } else {
            $this->tag->setContent($this->renderChildren());
            return $this->tag->render();
        }
    }
}
