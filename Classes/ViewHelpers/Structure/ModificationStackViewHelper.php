<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers\Structure;

use Buepro\Pizpalue\Structure\ModificationStack;
use Buepro\Pizpalue\Structure\VariantsModifierStack;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Mainly used for debugging purposes.
 * ATTENTION: The modification stack is only built when the final variants are calculated.
 *
 * @see VariantsModifierStack::getVariants()
 */
class ModificationStackViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('as', 'string', 'Name of variable to create.');
    }

    /**
     * @return mixed
     */
    public function render()
    {
        $result = GeneralUtility::makeInstance(ModificationStack::class)->getStack();
        if (isset($this->arguments['as']) && $this->arguments['as'] !== '') {
            $this->renderingContext->getVariableProvider()->add($this->arguments['as'], $result);
            return '';
        }
        return $result;
    }
}
