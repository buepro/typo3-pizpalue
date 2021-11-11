<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Service;

use Buepro\Pizpalue\Structure\TypoScript;
use Buepro\Pizpalue\Structure\VariantsModifier;
use Buepro\Pizpalue\Structure\VariantsModifierStack;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class BackendlayoutService
{
    /**
     * Reference to the parent (calling) cObject set from TypoScript
     *
     * @var ContentObjectRenderer
     */
    public $cObj;

    /**
     * @param string $content
     * @param array $conf
     */
    public function pushVariantsModifier(string $content, array $conf): void
    {
        $variantsModifier = new VariantsModifier();
        if (isset($conf['backendlayout']) && isset($this->cObj->data['colPos'])) {
            $backendlayout = $this->cObj->cObjGetSingle($conf['backendlayout'], $conf['backendlayout.']);
            $colPos = (int) $this->cObj->data['colPos'];
            $variantsModification = TypoScript::getVariants(sprintf(
                '%s.%s.%s',
                'lib.contentElement.settings.responsiveimages.backendlayout',
                $backendlayout,
                $colPos
            ));
            $variantsModifier->setModification((array)$variantsModification);
        }
        VariantsModifierStack::pushVariantsModifier($variantsModifier);
    }

    public function popVariantsModifier(): void
    {
        VariantsModifierStack::popVariantsModifier();
    }
}
