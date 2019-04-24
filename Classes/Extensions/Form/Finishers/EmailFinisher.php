<?php


namespace Buepro\Pizpalue\Extensions\Form\Finishers;


use
    TYPO3\CMS\Form\Domain\Runtime\FormRuntime,
    TYPO3\CMS\Fluid\View\StandaloneView,
    TYPO3\CMS\Form\Domain\Finishers\Exception\FinisherException;


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
        foreach(['senderName','senderAddress','recipientName','recipientAddress','subject'] as $key) {
            $parsedOptions[$key] = $this->parseOption($key);
        }
        $standaloneView->assign('finisherOptions',$parsedOptions);
        return $standaloneView;
    }
}