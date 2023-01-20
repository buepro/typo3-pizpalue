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

class ScaffoldService extends AbstractService
{
    public function process(): void
    {
        $this->handleContactData();
    }

    protected function handleContactData(): self
    {
        if (($pageUidWithTablePrefix = $this->getPropertyValueByFieldName('scaffold_contact_button_page_uid')) !== null) {
            $this->typoScriptMapper->bufferProperty(
                TcaUtility::getMappingPath('scaffold_contact_button_page_uid'),
                substr($pageUidWithTablePrefix, 6)
            );
        }
        return $this;
    }
}
