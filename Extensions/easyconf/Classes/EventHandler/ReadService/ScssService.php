<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\ReadService;

use Buepro\Easyconf\Utility\GeneralUtility as EasyconfGeneralUtility;
use Buepro\Pizpalue\Easyconf\EventHandler\PersistService\ScssService as ScssPersistService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ScssService extends AbstractService
{
    public function process(): array
    {
        $this->readDeclarations();
        return $this->formFields;
    }

    protected function readDeclarations(): self
    {
        $file = GeneralUtility::getFileAbsFileName(
            $this->fileService->getFullPath(ScssPersistService::RELATIVE_STORAGE_TS_PATH) .
            $this->fileService->getRootFileName(ScssPersistService::FILE_NAME)
        );
        if (!is_string($content = EasyconfGeneralUtility::readTextFile($file))) {
            return $this;
        }
        $this->formFields['style_scss_declarations'] = $content;
        return $this;
    }
}
