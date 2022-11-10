<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') || die('Access denied.');

(static function (): void {
    foreach (['icon_color', 'icon_background'] as $fieldName) {
        $fieldColumn = &$GLOBALS['TCA']['tt_content']['columns'][$fieldName];
        unset($fieldColumn['displayCond']);
        $fieldColumn['config']['valuePicker'] = [
            'items' => \Buepro\Pizpalue\Helper\TcaConfig::getColorItems(false, 'var(--bs-%s)'),
        ];
        unset($fieldColumn);
    }
})();
