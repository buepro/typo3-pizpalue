<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers;

/**
 * FrameViewHelper
 */
class BootstrapPackageFrameViewHelper extends \BK2K\BootstrapPackage\ViewHelpers\FrameViewHelper
{
    /**
     * Initialize additional arguments.
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('ppData', 'array', 'Pizpalue data', true);
    }
}
