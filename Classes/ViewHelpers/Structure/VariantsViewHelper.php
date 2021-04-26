<?php
declare(strict_types=1);

namespace Buepro\Pizpalue\ViewHelpers\Structure;

use Buepro\Pizpalue\Utility\StructureVariantsUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * To get the current variants
 *
 * @see StructureVariantsUtility
 */
class VariantsViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('as', 'string', 'Name of variable to create.', true);
        $this->registerArgument('initialVariants', 'array|string', 'Array or string specifying the initial variants. A string can reference a variants field from the current content record or contain the complete or just the last segment from the default TS path.', false);
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
        $renderingContext->getVariableProvider()->add(
            $arguments['as'],
            StructureVariantsUtility::getVariants($arguments['initialVariants'])
        );
    }
}
