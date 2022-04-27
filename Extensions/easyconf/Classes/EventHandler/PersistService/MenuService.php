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

class MenuService extends AbstractService
{
    public function process(): void
    {
        $this->handleMainMenu()->handleFastMenu();
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
            ($this->formFields['menu_main_subpage_style'] !== 'none' || $this->formFields['menu_main_subpage_type'] !== 'none')
        ) {
            $this->typoScriptMapper->bufferScript(sprintf(
                "[tree.level > %d]\r\n    page.theme.navigation {\r\n        style = %s\r\n        type = %s\r\n    }\r\n[end]",
                $this->typoScriptService->getTreeLevel(),
                $this->formFields['menu_main_subpage_style'],
                $this->formFields['menu_main_subpage_type'],
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
}
