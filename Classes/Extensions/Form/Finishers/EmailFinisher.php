<?php
declare(strict_types = 1);

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Extensions\Form\Finishers;

use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Form\Domain\Finishers\Exception\FinisherException;
use TYPO3\CMS\Form\Domain\Runtime\FormRuntime;

class EmailFinisher extends \TYPO3\CMS\Form\Domain\Finishers\EmailFinisher
{
    /**
     * In addition to the parents action the finisher options are passed to the template.
     *
     * @param FormRuntime $formRuntime
     * @return StandaloneView
     * @throws FinisherException
     */
    protected function initializeStandaloneView(FormRuntime $formRuntime): StandaloneView
    {
        $standaloneView = parent::initializeStandaloneView($formRuntime);
        $parsedOptions = [];
        foreach (['senderName', 'senderAddress', 'recipientName', 'recipientAddress', 'subject'] as $key) {
            $parsedOptions[$key] = $this->parseOption($key);
        }
        $standaloneView->assign('finisherOptions', $parsedOptions);
        return $standaloneView;
    }
}
