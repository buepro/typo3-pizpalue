<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\PersistService;

use Buepro\Easyconf\Utility\GeneralUtility as EasyconfGeneralUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ScssService extends AbstractService
{
    public const FILE_NAME = 'EasyconfR%d.scss';
    public const SETUP_FILE = 'fileadmin/pizpalue/extensions/easyconf/Configuration/TypoScript/Autogenerated/SetupScssR%d.typoscript';
    public const RELATIVE_STORAGE_TS_PATH = 'module.tx_easyconf.persistence.storageRelativeScssPath';

    /** @var string|null  */
    protected $declarationFile;

    public function process(): void
    {
        if ((int)$this->formFields['style_global_enable_scss'] !== 1) {
            return;
        }
        $this->generateSetupFile()->persistDeclarations();
    }

    public function getDeclarationFile(): string
    {
        return $this->declarationFile ?? ($this->declarationFile =
            $this->fileService->getFullPath(self::RELATIVE_STORAGE_TS_PATH) .
            $this->fileService->getRootFileName(self::FILE_NAME));
    }

    protected function generateSetupFile(): self
    {
        $content = [];
        $content[] = sprintf(
            '[traverse(site("configuration"), "easyconf/data/style/global/enableScss") == 1 and %d in tree.rootLineIds]',
            $this->typoScriptService->getRootPageUid()
        );
        $content[] = sprintf('    page.includeCSS.pizpalueEasyconf = %s', $this->getDeclarationFile());
        $content[] = '[GLOBAL]';
        EasyconfGeneralUtility::writeTextFile(
            GeneralUtility::getFileAbsFileName($this->fileService->getRootFileName(self::SETUP_FILE)),
            implode("\r\n", $content)
        );
        return $this;
    }

    protected function persistDeclarations(): self
    {
        if (($declarations = $this->formFields['style_scss_declarations'] ?? '') === '') {
            return $this;
        }
        EasyconfGeneralUtility::writeTextFile(
            GeneralUtility::getFileAbsFileName($this->getDeclarationFile()),
            trim($declarations)
        );
        return $this;
    }
}
