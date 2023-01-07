<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\PersistService;

use Buepro\Easyconf\Utility\TcaUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FontService extends AbstractService
{
    /** @var string */
    protected $googleFont;
    /** @var string */
    protected $googleFontWeight;
    /** @var string */
    protected $googleHeadingsFont;
    /** @var string */
    protected $googleHeadingsFontWeight;

    public function process(): void
    {
        $this->handleGoogleFont()->handleDifferentHeadingsFont();
    }

    public function getGoogleFont(): string
    {
        return $this->googleFont ?? $this->getUrlSegmentFromField('font_google_font');
    }

    public function getGoogleFontWeight(): string
    {
        return $this->googleFontWeight ?? $this->getUrlSegmentFromField('font_google_weight');
    }

    public function getGoogleHeadingsFont(): string
    {
        return $this->googleHeadingsFont ?? $this->getUrlSegmentFromField('font_google-headings-webfont');
    }

    public function getGoogleHeadingsFontWeight(): string
    {
        return $this->googleHeadingsFontWeight ?? $this->getUrlSegmentFromField('font_google-headings-webfont-weight');
    }

    protected function handleDifferentHeadingsFont(): self
    {
        if (!(bool)$this->getPropertyValueByFieldName('font_control_enable_headings_font')) {
            $this->typoScriptMapper->removePropertyFromBuffer(
                TcaUtility::getMappingPath('font_google-headings-webfont')
            );
            $this->typoScriptMapper->removePropertyFromBuffer(
                TcaUtility::getMappingPath('font_google-headings-webfont-weight')
            );
            $this->typoScriptMapper->removePropertyFromBuffer(
                TcaUtility::getMappingPath('font_headings_headings-font-family')
            );
        }
        return $this;
    }

    protected function handleGoogleFont(): self
    {
        if (
            (bool)$this->getPropertyValueByFieldName('font_control_google_font_enable') &&
            $this->getGoogleFont() !== ''
        ) {
            $this->enableGoogleFont();
            return $this;
        }
        $this->disableGoogleFont();
        return $this;
    }

    protected function enableGoogleFont(): void
    {
        $this->enableGoogleNormalFont();
        if (
            (bool)$this->getPropertyValueByFieldName('font_control_enable_headings_font') &&
            $this->getGoogleHeadingsFont() !== ''
        ) {
            $this->enableGoogleHeadingsFont();
        } else {
            $this->disableGoogleHeadingsFont();
        }
    }

    protected function disableGoogleFont(): void
    {
        $field = 'font_normal_font-family-base';
        $this->typoScriptMapper->bufferProperty(TcaUtility::getMappingPath($field), $this->excludeItemFromList(
            $this->getPropertyValueByFieldName($field),
            '"#{$google-webfont}"'
        ));
        $this->disableGoogleHeadingsFont();
        $this->typoScriptMapper->removePropertyFromBuffer('pizpalue.style.googleFontsUrl');
    }

    protected function enableGoogleNormalFont(): self
    {
        if ($this->getGoogleFontWeight() === '') {
            $this->googleFontWeight = '400';
            $this->typoScriptMapper->bufferProperty(
                TcaUtility::getMappingPath('font_google_weight'),
                $this->getGoogleFontWeight()
            );
        }
        $this
            ->addGoogleFontToFontFamily(
                'font_normal_font-family-base',
                '"#{$google-webfont}"'
            )
            ->addGoogleFontWeightToFontWeight(
                'font_normal_font-weight-normal',
                $this->getGoogleFontWeight()
            );
        return $this;
    }

    protected function enableGoogleHeadingsFont(): self
    {
        if ($this->getGoogleHeadingsFontWeight() === '') {
            $this->googleHeadingsFontWeight = '400';
            $this->typoScriptMapper->bufferProperty(
                TcaUtility::getMappingPath('font_google-headings-webfont-weight'),
                $this->getGoogleHeadingsFontWeight()
            );
        }
        $this
            ->addGoogleFontToFontFamily(
                'font_headings_headings-font-family',
                '"#{$google-headings-webfont}"'
            )
            ->addGoogleFontWeightToFontWeight(
                'font_headings_headings-font-weight',
                $this->getGoogleHeadingsFontWeight()
            );
        $this->typoScriptMapper->bufferProperty('pizpalue.style.googleFontsUrlHeadingsSegment', sprintf(
            '|%s:%s',
            $this->getGoogleHeadingsFont(),
            $this->getGoogleHeadingsFontWeight(),
        ));
        return $this;
    }

    protected function disableGoogleHeadingsFont(): void
    {
        $field = 'font_headings_headings-font-family';
        $this->typoScriptMapper->bufferProperty(TcaUtility::getMappingPath($field), $this->excludeItemFromList(
            $this->getPropertyValueByFieldName($field),
            '"#{$google-headings-webfont}"'
        ));
        $this->typoScriptMapper->removePropertyFromBuffer('pizpalue.style.googleFontsUrlHeadingsSegment');
    }

    protected function addGoogleFontToFontFamily(string $field, string $scssFontInterpolation): self
    {
        $this->typoScriptMapper->bufferProperty(TcaUtility::getMappingPath($field), $this->includeItemInList(
            $this->getPropertyValueByFieldName($field),
            $scssFontInterpolation
        ));
        return $this;
    }

    protected function addGoogleFontWeightToFontWeight(string $field, string $fontWeights): self
    {
        if (
            ($currentFontWeight = trim($this->getPropertyValueByFieldName($field))) === '' ||
            strpos($fontWeights, $currentFontWeight) === false &&
            ($suggestedFontWeight = (int)(GeneralUtility::trimExplode(',', $fontWeights)[0] ?? 400)) > 0
        ) {
            $this->typoScriptMapper->bufferProperty(TcaUtility::getMappingPath($field), (string)$suggestedFontWeight);
        }
        return $this;
    }

    protected function includeItemInList(string $list, string $item): string
    {
        if (strpos($list, $item) !== false) {
            return $list;
        }
        if (trim($list) === '') {
            $list = 'sans-serif';
        }
        $items = array_merge([$item], GeneralUtility::trimExplode(',', $list, true));
        return implode(', ', $items);
    }

    protected function excludeItemFromList(string $list, string $item): string
    {
        if (strpos($list, $item) === false) {
            return $list;
        }
        $list = str_replace($item, '', $list);
        return implode(', ', GeneralUtility::trimExplode(',', $list, true));
    }

    protected function getUrlSegmentFromField(string $field): string
    {
        return (string)str_replace(' ', '+', trim($this->getPropertyValueByFieldName($field)));
    }
}
