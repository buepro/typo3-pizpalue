<?php
declare(strict_types=1);

namespace Buepro\Pizpalue\Service;


use BK2K\BootstrapPackage\Utility\ImageVariantsUtility;
use Buepro\Pizpalue\Domain\Model\VariantsModifier;
use Buepro\Pizpalue\Utility\StructureVariantsUtility;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Class VariantsService
 * @package Buepro\Pizpalue\Service
 */
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
    public function pushVariantsModifier()
    {
        $data = $this->cObj->data;
        // default variants
        $variants = ImageVariantsUtility::getImageVariants();
        if ($this->cObj->getCurrentTable() === 'tt_content' && $data['CType']) {
            $variantsConf = StructureVariantsUtility::getTypoScriptValue(
                'lib.contentElement.settings.responsiveimages.contentelements.' . $data['CType']);
            if ($variantsConf) {
                $methodName = 'getVariantsFor' . GeneralUtility::underscoredToUpperCamelCase($data['CType']);
                if (method_exists($this, $methodName)) {
                    // The setup might contain several variants. Get the one that applies to the current content element
                    $variants = $this->{$methodName}($variantsConf);
                } else {
                    // Just one variants has been defined
                    $variants = $variantsConf;
                }
            }
        }
        $variantsModifier = GeneralUtility::makeInstance(VariantsModifier::class);
        $variantsModifier->setVariants($variants);
        StructureVariantsUtility::pushVariantsModifier($variantsModifier);
    }

    public function popVariantsModifier()
    {
        StructureVariantsUtility::popVariantsModifier();
    }
}
