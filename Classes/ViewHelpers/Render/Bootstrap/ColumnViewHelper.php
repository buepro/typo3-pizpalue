<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Render\Bootstrap;

use Buepro\Pizpalue\Utility\StructureMultiplierUtility;
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
    /**
     * @var array
     */
    private static $multiplierStack = [];

    protected $tagName = 'div';

    /**
     * Returns last added element from $multiplierStack
     *
     * @return array|mixed
     */
    private static function getCurrentMultiplier()
    {
        $currentMultiplier = [];
        if (count(self::$multiplierStack)) {
            $currentMultiplier = array_pop(self::$multiplierStack);
            self::$multiplierStack[] = $currentMultiplier;
        }
        return $currentMultiplier;
    }

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerArgument('count', 'int', 'Column count in row', false, 1);
        $this->registerArgument('tagName', 'string', 'Tag name', false, 'div');
    }

    public function render()
    {
        $this->tagName = $this->arguments['tagName'];
        if ($GLOBALS['TSFE'] instanceof TypoScriptFrontendController) {
            // Initialize
            $currentMultiplier = self::getCurrentMultiplier();

            // Get multiplier for current structure
            $multiplier = StructureMultiplierUtility::getMultiplierForColumn(
                $currentMultiplier,
                $this->arguments['class'],
                $this->arguments['count']
            );

            // Push multiplier -> render content -> pop multiplier
            self::$multiplierStack[] = $multiplier;
            $GLOBALS['TSFE']->register['structureMultiplier'] = $multiplier;
            $this->tag->setContent($this->renderChildren());
            $content = $this->tag->render();
            array_pop(self::$multiplierStack);
            $GLOBALS['TSFE']->register['structureMultiplier'] = self::getCurrentMultiplier();
            return $content;
        } else {
            $this->tag->setContent($this->renderChildren());
            return $this->tag->render();
        }
    }
}
