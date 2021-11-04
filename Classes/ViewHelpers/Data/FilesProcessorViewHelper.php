<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Data;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\DataProcessing\FilesProcessor;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class FilesProcessorViewHelper
 */
class FilesProcessorViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('as', 'string', 'Name of variable to create.', false);
        $this->registerArgument('data', 'array', 'Data holding the files.', true);
        $this->registerArgument('field', 'string', 'Field within data holding the files.', true);
        $this->registerArgument('table', 'string', 'The table name the data belongs to', false, 'tt_content');
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
        $result = [];
        if (isset($arguments['data'][$arguments['field']])) {
            $cObjRenderer = new ContentObjectRenderer();
            $filesProcessor = new FilesProcessor();
            $cObjRenderer->start($arguments['data'], $arguments['table']);
            $processorConfiguration = [
                'references.' => [
                    'fieldName' => $arguments['field'],
                ],
            ];
            $result = $filesProcessor->process($cObjRenderer, [], $processorConfiguration, [])['files'] ?? [];
        }
        if ($arguments['as']) {
            $renderingContext->getVariableProvider()->add($arguments['as'], $result);
        } else {
            return $result;
        }
    }
}
