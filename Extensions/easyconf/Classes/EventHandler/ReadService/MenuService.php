<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\ReadService;

class MenuService extends AbstractService
{
    public function process(): array
    {
        $this->handleFastMenu()->handleScrollMenu();
        return $this->formFields;
    }

    protected function handleFastMenu(): self
    {
        foreach (['menu_fast_items_first_content_uid', 'menu_fast_items_second_content_uid',
                     'menu_fast_items_third_content_uid'] as $field) {
            if (isset($this->formFields[$field]) && (int)$this->formFields[$field] > 0) {
                $this->formFields[$field] = 'tt_content_' . $this->formFields[$field];
            }
        }
        foreach (['menu_fast_items_first_page_uid', 'menu_fast_items_second_page_uid',
                     'menu_fast_items_third_page_uid'] as $field) {
            if (isset($this->formFields[$field]) && (int)$this->formFields[$field] > 0) {
                $this->formFields[$field] = 'pages_' . $this->formFields[$field];
            }
        }
        return $this;
    }

    protected function handleScrollMenu(): self
    {
        $field = 'menu_scroll_page_uid';
        if (isset($this->formFields[$field]) && (int)$this->formFields[$field] > 0) {
            $this->formFields[$field] = 'pages_' . $this->formFields[$field];
        }
        return $this;
    }
}
