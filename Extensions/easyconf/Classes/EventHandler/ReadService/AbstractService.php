<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\ReadService;

use Buepro\Easyconf\Mapper\TypoScriptConstantMapper;
use Buepro\Easyconf\Service\FileService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractService
{
    /** @var array */
    protected $formFields;
    /** @var TypoScriptConstantMapper */
    protected $typoScriptConstantMapper;
    /** @var FileService */
    protected $fileService;

    public function __construct(array $formFields)
    {
        $this->formFields = $formFields;
        $this->typoScriptConstantMapper = GeneralUtility::makeInstance(TypoScriptConstantMapper::class);
        $this->fileService = GeneralUtility::makeInstance(FileService::class);
    }

    /**
     * @return array Processed form fields
     */
    abstract public function process(): array;
}
