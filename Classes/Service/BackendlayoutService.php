<?php
declare(strict_types=1);

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Service;

use Buepro\Pizpalue\Domain\Model\VariantsModifier;
use Buepro\Pizpalue\Utility\StructureVariantsUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
    public function pushVariantsModifier(string $content, array $conf)
    {
        $variantsModifier = GeneralUtility::makeInstance(VariantsModifier::class);
        if (isset($conf['backendlayout']) && isset($this->cObj->data['colPos'])) {
            $backendlayout = $this->cObj->cObjGetSingle($conf['backendlayout'], $conf['backendlayout.']);
            $colPos = (int) $this->cObj->data['colPos'];
            $variantsConf = StructureVariantsUtility::getTypoScriptValue(sprintf(
                '%s.%s.%s',
                'lib.contentElement.settings.responsiveimages.backendlayout',
                $backendlayout,
                $colPos
            ));
            if ($variantsConf) {
                if (isset($variantsConf['multiplier'])) {
                    $variantsModifier->setMultiplier($variantsConf['multiplier']);
                }
                if (isset($variantsConf['gutters'])) {
                    $variantsModifier->setGutter($variantsConf['gutters']);
                }
                if (isset($variantsConf['corrections'])) {
                    $variantsModifier->setCorrection($variantsConf['corrections']);
                }
            }
        }
        StructureVariantsUtility::pushVariantsModifier($variantsModifier);
    }

    public function popVariantsModifier()
    {
        StructureVariantsUtility::popVariantsModifier();
    }
}
