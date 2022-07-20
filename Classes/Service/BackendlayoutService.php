<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Service;

use Buepro\Pizpalue\Structure\Service\TypoScriptService;
use Buepro\Pizpalue\Structure\VariantsModifier;
use Buepro\Pizpalue\Structure\VariantsModifierStack;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class BackendlayoutService
{
    /** @var VariantsModifierStack $variantsModifierStack */
    protected $variantsModifierStack;

    /**
     * Reference to the parent (calling) cObject set from TypoScript
     *
     * @var ContentObjectRenderer
     */
    public $cObj;

    public function __construct()
    {
        $this->variantsModifierStack = GeneralUtility::makeInstance(VariantsModifierStack::class);
    }

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
            $variantsModification = (new TypoScriptService())->getVariants(sprintf(
                '%s.%s.%s',
                'lib.contentElement.settings.responsiveimages.backendlayout',
                $backendlayout,
                $colPos
            ));
            $variantsModifier->setModification((array)$variantsModification);
        }
        $this->variantsModifierStack->pushVariantsModifier($variantsModifier);
    }

    public function popVariantsModifier(): void
    {
        $this->variantsModifierStack->popVariantsModifier();
    }
}
