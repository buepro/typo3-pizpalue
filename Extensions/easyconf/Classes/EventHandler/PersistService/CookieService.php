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

class CookieService extends AbstractService
{
    public function process(): void
    {
        if ((bool)$this->getPropertyValueByFieldName('cookie_static')) {
            $this->typoScriptMapper->bufferProperty(
                TcaUtility::getMappingPath('cookie_position'),
                'top'
            );
        }
    }
}
