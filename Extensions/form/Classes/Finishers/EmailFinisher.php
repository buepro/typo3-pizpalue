<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Form\Finishers;

use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Form\Domain\Runtime\FormRuntime;

class EmailFinisher extends \TYPO3\CMS\Form\Domain\Finishers\EmailFinisher
{
    /**
     * In addition to the parents action the finisher options are passed to the template.
     */
    protected function initializeFluidEmail(FormRuntime $formRuntime): FluidEmail
    {
        $fluidEmail = parent::initializeFluidEmail($formRuntime);
        $parsedOptions = [];
        foreach (['senderName', 'senderAddress', 'recipientName', 'recipientAddress', 'subject'] as $key) {
            $parsedOptions[$key] = $this->parseOption($key);
        }
        $fluidEmail->assign('finisherOptions', $parsedOptions);
        return $fluidEmail;
    }
}
