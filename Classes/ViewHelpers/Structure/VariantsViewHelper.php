<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Structure;

use Buepro\Pizpalue\Structure\VariantsModifierStack;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * To get the current variants
 *
 * @see VariantsModifierStack
 */
class VariantsViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('as', 'string', 'Name of variable to create.', true);
        $this->registerArgument('initialVariants', 'array|string', 'Array or string specifying the initial variants. A string can reference a variants field from the current content record or contain the complete or just the last segment from the default TS path.', false);
    }

    /**
     * @return mixed
     */
    public function render()
    {
        $result = (GeneralUtility::makeInstance(VariantsModifierStack::class))
            ->getVariants($this->arguments['initialVariants'] ?? '');
        if (isset($this->arguments['as']) && $this->arguments['as'] !== '') {
            $this->renderingContext->getVariableProvider()->add($this->arguments['as'], $result);
            return '';
        }
        return $result;
    }
}
