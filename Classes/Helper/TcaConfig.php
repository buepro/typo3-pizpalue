<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Helper;

class TcaConfig
{
    public const BOOTSTRAP_PACKAGE_COLORS = ['primary', 'secondary', 'complementary', 'tertiary', 'quaternary', 'light', 'dark'];
    public const LL_PREFIX = 'LLL:EXT:pizpalue/Resources/Private/Language/locallang.xml:';

    public static function getColorItems(bool $includeNormal = true, string $format = ''): array
    {
        $result = [];
        if ($includeNormal) {
            $result[] = [self::LL_PREFIX . 'normal', ''];
        }
        foreach (self::BOOTSTRAP_PACKAGE_COLORS as $color) {
            $value = $color;
            if ($format !== '') {
                $value = sprintf($format, $value);
            }
            $result[] = [self::LL_PREFIX . $color, $value];
        }
        return $result;
    }

    public static function getColorConfig(): array
    {
        return [
            'type' => 'input',
            'valuePicker' => [
                'items' => self::getColorItems(false, 'var(--bs-%s)'),
            ]
        ];
    }
}
