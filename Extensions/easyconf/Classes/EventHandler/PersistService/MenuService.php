<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\PersistService;

use Buepro\Easyconf\Utility\TcaUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Form\Service\TranslationService;

class MenuService extends AbstractService
{
    public function process(): void
    {
        $this->handleMainMenu()->handleFastMenu()->handleScrollMenu();
    }

    protected function handleMainMenu(): self
    {
        foreach (['style', 'subpage_style', 'type', 'subpage_type'] as $field) {
            $field = 'menu_main_' . $field;
            if (
                ($path = TcaUtility::getMappingPath($field)) !== null &&
                $this->typoScriptMapper->getProperty($path) === 'none'
            ) {
                $this->typoScriptMapper->removePropertyFromBuffer($path);
            }
        }
        if (
            $this->formFields['menu_main_enable_subpage_definition'] === '1' &&
            (
                ($this->formFields['menu_main_subpage_style'] ?? 'none') !== 'none' ||
                ($this->formFields['menu_main_subpage_type'] ?? 'none') !== 'none'
            )
        ) {
            $this->typoScriptMapper->bufferScript(sprintf(
                "[tree.level > %d]\r\n    page.theme.navigation {\r\n        style = %s\r\n        type = %s\r\n    }\r\n[end]",
                $this->typoScriptService->getTreeLevel(),
                $this->formFields['menu_main_subpage_style'] ?? 'default',
                $this->formFields['menu_main_subpage_type'] ?? '',
            ));
        }
        return $this;
    }

    protected function handleFastMenu(): self
    {
        foreach (['menu_fast_items_first_content_uid', 'menu_fast_items_second_content_uid', 'menu_fast_items_third_content_uid'] as $field) {
            if (
                isset($this->formFields[$field]) &&
                strpos($this->formFields[$field], 'tt_content') === 0 &&
                ($path = TcaUtility::getMappingPath($field)) !== null
            ) {
                $this->typoScriptMapper->bufferProperty($path, substr($this->formFields[$field], 11));
            }
        }
        foreach (['menu_fast_items_first_page_uid', 'menu_fast_items_second_page_uid', 'menu_fast_items_third_page_uid'] as $field) {
            if (
                isset($this->formFields[$field]) &&
                strpos($this->formFields[$field], 'pages') === 0 &&
                ($path = TcaUtility::getMappingPath($field)) !== null
            ) {
                $this->typoScriptMapper->bufferProperty($path, substr($this->formFields[$field], 6));
            }
        }
        return $this;
    }

    protected function handleScrollMenu(): self
    {
        $field = 'menu_scroll_page_uid';
        if (($path = TcaUtility::getMappingPath($field)) === null) {
            return $this;
        }
        $current = $this->formFields[$field] ?? '';
        if (strpos($current, 'pages') === 0) {
            $current = substr($this->formFields[$field], 6);
            $this->typoScriptMapper->bufferProperty($path, $current);
        }
        $previous = $this->typoScriptService->getConstantByPath($path);
        $dataKeyMappingPath = TcaUtility::getMappingPath('menu_scroll_data_key');
        $dataKey = $this->typoScriptMapper->getProperty($dataKeyMappingPath);
        $menuIdMappingPath = TcaUtility::getMappingPath('menu_scroll_menu_id');
        $menuId = $this->typoScriptMapper->getProperty($menuIdMappingPath);
        if ($previous === $current) {
            return $this;
        }
        if ($current !== '' && $dataKey === 'scrollnavigation' && $menuId === 'pp-scroll-nav') {
            $this->typoScriptMapper->bufferProperty($dataKeyMappingPath, 'mainnavigation');
            $this->typoScriptMapper->bufferProperty($menuIdMappingPath, 'mainnavigation');
        }
        if ($current === '' && $dataKey === 'mainnavigation' && $menuId === 'mainnavigation') {
            $this->typoScriptMapper->bufferProperty($dataKeyMappingPath, 'scrollnavigation');
            $this->typoScriptMapper->bufferProperty($menuIdMappingPath, 'pp-scroll-nav');
        }
        $this->addFlashMessage('flashMessage.scrollMenuPropertyChange');
        return $this;
    }

    protected function addFlashMessage(string $key): void
    {
        $translationService = GeneralUtility::makeInstance(TranslationService::class);
        $text = $translationService->translate(
            'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang.xlf:' . $key
        );
        $message = GeneralUtility::makeInstance(
            FlashMessage::class,
            $text,
            '',
            FlashMessage::INFO,
            true
        );
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        /** @extensionScannerIgnoreLine */
        $messageQueue->addMessage($message);
    }
}
