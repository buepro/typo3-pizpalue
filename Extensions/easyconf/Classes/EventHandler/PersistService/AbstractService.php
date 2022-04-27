<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\PersistService;

use Buepro\Easyconf\Mapper\Service\SiteConfigurationService;
use Buepro\Easyconf\Mapper\Service\TypoScriptService;
use Buepro\Easyconf\Mapper\SiteConfigurationMapper;
use Buepro\Easyconf\Mapper\TypoScriptConstantMapper;
use Buepro\Easyconf\Service\FileService;
use Buepro\Easyconf\Service\PropertyService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractService
{
    /** @var TypoScriptConstantMapper */
    protected $typoScriptMapper;
    /** @var SiteConfigurationMapper */
    protected $siteConfigurationMapper;
    /** @var TypoScriptService */
    protected $typoScriptService;
    /** @var SiteConfigurationService */
    protected $siteConfigurationService;
    /** @var PropertyService */
    protected $propertyService;
    /** @var FileService */
    protected $fileService;
    /** @var array */
    protected $formFields = [];
    /** @var array */
    protected $configurationRecord = [];

    public function __construct(array $formFields, array $configurationRecord)
    {
        $this->formFields = $formFields;
        $this->configurationRecord = $configurationRecord;
        $this->typoScriptMapper = GeneralUtility::makeInstance(TypoScriptConstantMapper::class);
        $this->siteConfigurationMapper = GeneralUtility::makeInstance(SiteConfigurationMapper::class);
        $this->typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
        $this->siteConfigurationService = GeneralUtility::makeInstance(SiteConfigurationService::class);
        $this->propertyService = GeneralUtility::makeInstance(PropertyService::class);
        $this->fileService = GeneralUtility::makeInstance(FileService::class);
    }

    abstract public function process(): void;

    /**
     * @return mixed
     */
    public function getPropertyValueByFieldName(string $fieldName)
    {
        return $this->formFields[$fieldName] ?? $this->propertyService->getFromMapperByFieldName($fieldName);
    }
}
