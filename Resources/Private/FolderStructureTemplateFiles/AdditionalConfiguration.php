<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'] = 'd.m.Y';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'] = 'H:i';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['phpTimeZone'] = 'Europe/Zurich';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['systemLocale'] = 'de_CH.utf8';
$GLOBALS['TYPO3_CONF_VARS']['BE']['lockSSL'] = true;

/**
 * Disables logging in production context
 */
if (in_array(\TYPO3\CMS\Core\Utility\GeneralUtility::getApplicationContext(), ['Production', 'Production/Staging'])) {
    // Removes the default writer configurations
    $GLOBALS['TYPO3_CONF_VARS']['LOG']['writerConfiguration'] = [];
    // Removes the writer configuration for depreciation log
    $GLOBALS['TYPO3_CONF_VARS']['LOG']['TYPO3']['CMS']['deprecations']['writerConfiguration'][\TYPO3\CMS\Core\Log\LogLevel::NOTICE] = [];
}
