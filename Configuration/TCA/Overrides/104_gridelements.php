<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3_MODE') || die();

(function () {
    $columnsOverrides = [];
    $columnsOverrides['frame_class']['config'] = [
        'default' => 'none',
    ];
    $GLOBALS['TCA']['tt_content']['types']['gridelements_pi1'] = array_merge(
        $GLOBALS['TCA']['tt_content']['types']['gridelements_pi1'],
        ['columnsOverrides' => $columnsOverrides]
    );
})();
