<?php
declare(strict_types=1);

namespace Buepro\Pizpalue\ViewHelpers\Structure;

use BK2K\BootstrapPackage\Utility\ImageVariantsUtility;
use Buepro\Pizpalue\Utility\StructureVariantsUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * To get the variants modifier stack. Mainly used for debugging purposes.
 */
class VariantsModifierStackViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('as', 'string', 'Name of variable to create.', true);
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
        $renderingContext->getVariableProvider()->add($arguments['as'], StructureVariantsUtility::getVariantsModifierStack());
    }
}
