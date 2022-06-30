<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use Buepro\Easyconf\Mapper\EasyconfMapper;
use Buepro\Easyconf\Mapper\TypoScriptConstantMapper;
use Buepro\Easyconf\Utility\TcaUtility;

defined('TYPO3') or die('Access denied.');

if (!\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('easyconf')) {
    return;
}

(static function () {
    $l10nFile = 'LLL:EXT:pizpalue/Extensions/easyconf/Resources/Private/Language/locallang_db_customer.xlf';
    $tca = &$GLOBALS['TCA']['tx_easyconf_configuration'];

    /**
     * Properties
     */
    $customerCompanyProperties = 'company, contactName';
    $customerUrlProperties = 'url, alternativeUrl';
    $customerAddressProperties = 'contactAddress, contactAddressAddition, contactZip, contactCity, contactCountry';
    $customerContactProperties = 'contactPhone, contactPhoneAlt, contactEmail, contactMessenger';
    $customerProperties = implode(', ', [$customerCompanyProperties, $customerAddressProperties,
        $customerContactProperties]);

    /**
     * Define columns
     */
    $propertyMaps = [
        TcaUtility::getPropertyMap(
            TypoScriptConstantMapper::class,
            'pizpalue.customer',
            $customerProperties,
            'customer'
        ),
        TcaUtility::getPropertyMap(
            EasyconfMapper::class,
            'pizpalue.customer',
            $customerUrlProperties,
            'customer'
        ),
    ];
    $tca['columns'] = array_replace($tca['columns'], TcaUtility::getColumns($propertyMaps, $l10nFile));

    /**
     * Define palettes
     */
    $tca['palettes'] = array_replace($tca['palettes'], [
        'paletteCustomerCompany' => TcaUtility::getPalette($customerCompanyProperties, 'customer'),
        'paletteCustomerUrl' => TcaUtility::getPalette($customerUrlProperties, 'customer'),
        'paletteCustomerAddress' => TcaUtility::getPalette($customerAddressProperties, 'customer'),
        'paletteCustomerContact' => TcaUtility::getPalette($customerContactProperties, 'customer'),
    ]);

    /**
     * Modify columns
     */
    $tca['columns']['customer_url']['config']['placeholder'] = 'https://www.domain.ch';
    $tca['columns']['customer_url']['exclude'] = 1;
    $tca['columns']['customer_alternative_url']['config']['placeholder'] = 'https://dev.domain.ch';

    unset($tca);
})();
