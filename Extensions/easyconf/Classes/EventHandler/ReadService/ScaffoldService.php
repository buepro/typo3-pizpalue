<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\ReadService;

use Buepro\Easyconf\Utility\TcaUtility;

class ScaffoldService extends AbstractService
{
    public function process(): array
    {
        $this->handleContactData();
        return $this->formFields;
    }

    protected function handleContactData(): self
    {
        $pageUid = $this->typoScriptConstantMapper->getProperty(
            TcaUtility::getMappingPath('scaffold_contact_button_page_uid')
        );
        $this->formFields['scaffold_contact_button_page_uid'] = 'pages_' . $pageUid;

        return $this;
    }
}
