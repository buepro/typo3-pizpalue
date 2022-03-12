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
use Buepro\Pizpalue\Utility\StructureVariantsUtility;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class ContentElementService
{
    /**
     * Reference to the parent (calling) cObject set from TypoScript
     *
     * @var ContentObjectRenderer
     */
    public $cObj;

    /**
     * @param string $text
     * @return array
     */
    protected function getFlexFormConfig(string $text)
    {
        /** @var FlexFormService $flexFormService */
        $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
        return $flexFormService->convertFlexFormContentToArray($text);
    }

    /**
     * getVariantsForPpModalDialog
     *
     * @param array $setup
     * @return array|null
     */
    protected function getVariantsForPpModalDialog(array $setup)
    {
        $flexFormConfig = $this->getFlexFormConfig($data = $this->cObj->data['pi_flexform']);
        if (isset($flexFormConfig['dialog_class'])) {
            if (false !== strpos($flexFormConfig['dialog_class'], 'modal-xl') && isset($setup['xl'])) {
                return $setup['xl'];
            }
            if (false !== strpos($flexFormConfig['dialog_class'], 'modal-lg') && isset($setup['lg'])) {
                return $setup['lg'];
            }
            if (false !== strpos($flexFormConfig['dialog_class'], 'modal-sm') && isset($setup['sm'])) {
                return $setup['sm'];
            }
        }
        return is_array($setup['default']) ? $setup['default'] : null;
    }

    /**
     * Pushes the variants modifier related to the content element defined by $this->cObj->data
     */
    public function pushVariantsModifier(): void
    {
        $data = $this->cObj->data;
        // default variants
        $variants = StructureVariantsUtility::getStructureVariants();
        if ($this->cObj->getCurrentTable() === 'tt_content' && $data['CType']) {
            $variantsConf = TypoScript::getVariants(
                'lib.contentElement.settings.responsiveimages.contentelements.' . $data['CType']
            );
            if ($variantsConf !== null) {
                $methodName = 'getVariantsFor' . GeneralUtility::underscoredToUpperCamelCase($data['CType']);
                if (method_exists($this, $methodName)) {
                    // The setup might contain several variants. Get the one that applies to the current content element
                    /** @phpstan-ignore-next-line */
                    $variants = $this->{$methodName}($variantsConf);
                } else {
                    // Just one variants has been defined
                    $variants = $variantsConf;
                }
            }
        }
        $variantsModifier = new VariantsModifier();
        $variantsModifier->setVariants($variants);
        VariantsModifierStack::pushVariantsModifier($variantsModifier);
    }

    public function popVariantsModifier(): void
    {
        VariantsModifierStack::popVariantsModifier();
    }

    public function modifyTitleTag(): void
    {
        if (
            ($records = $this->cObj->data['records'] ?? null) !== null &&
            isset($GLOBALS['TSFE']->config['config']['pageTitleProviders.']['news.']) &&
            strpos($records, 'tx_news_domain_model_news') !== false
        ) {
            unset($GLOBALS['TSFE']->config['config']['pageTitleProviders.']['news.']);
        }
    }
}
