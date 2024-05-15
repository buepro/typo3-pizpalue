<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

// Remove media restriction
unset($GLOBALS['TCA']['tt_content']['types']['media']['columnsOverrides']['assets']['config']['allowed']);
unset($GLOBALS['TCA']['tt_content']['types']['media']['columnsOverrides']['assets']['config']['disallowed']);
