<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\ReadService;

class FontService extends AbstractService
{
    public function process(): array
    {
        $this
            ->resetDefaultFontFieldValue('font_google-headings-webfont')
            ->resetDefaultFontFieldValue('font_google-headings-webfont-weight');
        return $this->formFields;
    }

    protected function resetDefaultFontFieldValue(string $field): self
    {
        if (isset($this->formFields[$field]) && strpos($this->formFields[$field], '{') === 0) {
            $this->formFields[$field] = '';
        }
        return $this;
    }
}
