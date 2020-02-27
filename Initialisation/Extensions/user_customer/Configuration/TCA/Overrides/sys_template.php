<?php

/*
 * This file is part of the package buepro/user_customer.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

call_user_func(
    function ($extensionKey) {
        /***************
         * Static templates
         */
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            $extensionKey,
            'Configuration/TypoScript',
            'Customer'
        );
    },
    'user_customer'
);
