<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Hook;

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class DataHandlerHook
 *
 * Sets the image variants when the following conditions are met:
 *   - The content element type is contained in $this->imageContainingTypes
 *   - The content element is not inside a structure element
 *   - The frame class changes between `none` and `default`
 */
class DataHandlerHook implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * @var string[]
     */
    private $imageContainingTypes = ['image', 'media', 'textpic', 'textmedia', 'carousel'];

    /**
     * Is initialized in case the imageVariants need to be reviewed.
     *
     * @var array
     */
    private $incomingFieldArray = [];

    /**
     * Hook: processDatamap_preProcessFieldArray
     */
    public function processDatamap_preProcessFieldArray(
        array &$incomingFieldArray,
        string $table,
        string $id,
        DataHandler $dataHandler
    ): void {
        $this->incomingFieldArray = [];
        if (
            $table === 'tt_content' && isset($incomingFieldArray['CType']) &&
            in_array($incomingFieldArray['CType'], $this->imageContainingTypes, true)
        ) {
            // Just review imageVariants when a be user conducts a change (not when importing data)
            $context = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class);
            if ((bool)$context->getPropertyFromAspect('backend.user', 'isLoggedIn')) {
                foreach ($incomingFieldArray as $key => $value) {
                    $this->incomingFieldArray[$key] = $value;
                }
            }
        }
    }

    private function setImageVariants(array &$fieldArray): void
    {
        if (isset($this->incomingFieldArray['frame_class'])) {
            // Check if we are inside a container element
            $insideContainer = ExtensionManagementUtility::isLoaded('container') &&
                isset($this->incomingFieldArray['tx_container_parent']) &&
                (int) $this->incomingFieldArray['tx_container_parent'] > 0;
            $insideGridelements = ExtensionManagementUtility::isLoaded('gridelements') &&
                isset($this->incomingFieldArray['tx_gridelements_columns']) &&
                (int) $this->incomingFieldArray['tx_gridelements_columns'] > 0;
            // Flux calculates the col pos with the containing container uid (e.g. containerUid * 100 + FluxColPos)
            $insideFlux = ExtensionManagementUtility::isLoaded('flux') &&
                (int) $this->incomingFieldArray['colPos'] > 99;

            // Backup the currently selected variants used for possible notification
            $initialVariants = $this->incomingFieldArray['tx_pizpalue_image_variants'];

            // Adapt the image variants if we are not in a structure element
            if (!$insideContainer && !$insideGridelements && !$insideFlux) {
                if ($this->incomingFieldArray['frame_class'] === 'none') {
                    $fieldArray['tx_pizpalue_image_variants'] = 'pageVariants';
                }
                if ($this->incomingFieldArray['frame_class'] === 'default') {
                    $fieldArray['tx_pizpalue_image_variants'] = 'variants';
                }
            }

            // Notify in case the image variants changed
            if (isset($fieldArray['tx_pizpalue_image_variants']) && $fieldArray['tx_pizpalue_image_variants'] !== $initialVariants) {
                $message = GeneralUtility::makeInstance(
                    FlashMessage::class,
                    $GLOBALS['LANG']->sL('LLL:EXT:pizpalue/Resources/Private/Language/Backend.xlf:flash-message-image-variants-changed'),
                    $GLOBALS['LANG']->sL('LLL:EXT:pizpalue/Resources/Private/Language/Backend.xlf:flash-message-change-setting'),
                    FlashMessage::INFO,
                    true
                );
                $flashMessageService = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
                /** @extensionScannerIgnoreLine */
                $messageQueue->addMessage($message);
            }
        }
    }

    /**
     * Hook: processDatamap_postProcessFieldArray
     *
     * Checks whether the user changed the frame class and adapts the image vraiants accordingly.
     *
     * @param string|int $id
     */
    public function processDatamap_postProcessFieldArray(
        string $status,
        string $table,
        $id,
        array &$fieldArray,
        DataHandler $dataHandler
    ): void {
        if (
            $this->incomingFieldArray !== [] &&
            $table === 'tt_content' &&
            ($status === 'new' || $status === 'update') &&
            isset($fieldArray['frame_class'])
        ) {
            $this->setImageVariants($fieldArray);
        }
    }
}
