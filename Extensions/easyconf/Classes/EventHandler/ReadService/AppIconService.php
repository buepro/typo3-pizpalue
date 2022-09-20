<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\ReadService;

use Buepro\Pizpalue\Easyconf\Utility\AppIconUtility;

class AppIconService extends AbstractService
{
    public function process(): array
    {
        $this->setAppIconText();
        return $this->formFields;
    }

    protected function setAppIconText(): self
    {
        $this->formFields['appicon_generator_text'] = AppIconUtility::getHtmlWithLineBreaks(
            $this->typoScriptConstantMapper->getProperty('pizpalue.appIcon.headerData')
        );

        return $this;
    }
}
