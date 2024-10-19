<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Data;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\DataProcessing\FilesProcessor;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class FilesProcessorViewHelper
 */
class FilesProcessorViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('as', 'string', 'Name of variable to create.', false);
        $this->registerArgument('data', 'array', 'Data holding the files.', true);
        $this->registerArgument('field', 'string', 'Field within data holding the files.', true);
        $this->registerArgument('table', 'string', 'The table name the data belongs to', false, 'tt_content');
    }

    /**
     * @return mixed
     */
    public function render()
    {
        $result = [];
        if (isset($this->arguments['data'][$this->arguments['field']])) {
            $cObjRenderer = new ContentObjectRenderer();
            $filesProcessor = new FilesProcessor();
            $cObjRenderer->start($this->arguments['data'], $this->arguments['table']);
            $processorConfiguration = [
                'references.' => [
                    'fieldName' => $this->arguments['field'],
                ],
            ];
            $result = $filesProcessor->process($cObjRenderer, [], $processorConfiguration, [])['files'] ?? [];
        }
        if (isset($this->arguments['as']) && $this->arguments['as'] !== '') {
            $this->renderingContext->getVariableProvider()->add($this->arguments['as'], $result);
            return '';
        }
        return $result;
    }
}
