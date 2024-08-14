<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Service;

use Buepro\Pizpalue\Helper\AssetHelper;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FrameDataService
{
    private AssetHelper $assetHelper;
    private AssetCollector $assetCollector;

    public function __construct()
    {
        $this->assetHelper = GeneralUtility::makeInstance(AssetHelper::class);
        $this->assetCollector = GeneralUtility::makeInstance(AssetCollector::class);
    }

    public function getData(array $data, array $pizpalueConstants): array
    {
        $result = [
            'classes' => [],
            'innerClasses' => [],
            'styles' => [],
            'attributes' => [],
            'isTile' => false,
            'hasCssAnimation' => false,
            'hasScrollAnimation' => false,
            'optimizeLinkTargets' => (bool) ($pizpalueConstants['seo']['optimizeLinkTargets'] ?? true),
        ];
        $this->addClasses($data, $result);
        $this->addInnerClasses($data, $result);
        $this->addStylesAndAttributes($data, $result);
        $this->addAnimation($data, $pizpalueConstants, $result);
        $this->addTiles($data, $result);
        $this->addJoshAssets($pizpalueConstants, $result);
        $this->addTwikitoAssets($result);
        $this->addAnimateCssAssets($result);
        $this->addFramelessClasses($data, $result);
        return $result;
    }

    private function getAttributes(string $attributes): array
    {
        // remove spaces before and after `=`
        $attributes = preg_replace('/(\s*=\s*)/', '=', $attributes);
        if ($attributes === null) {
            return [];
        }
        $result = preg_match_all('/([\w-]+="[^"]*")/', $attributes, $matches);
        if ($result !== false && $result > 0) {
            return $matches[0];
        }
        return [];
    }

    private function addAnimateCssToAssetCollector(): void
    {
        $this->assetHelper->includeAnimateCss();
    }

    private function addClasses(array $data, array &$result): void
    {
        if (($layout = trim((string)($data['layout'] ?? '0'))) !== '0' && strpos($layout, 'pp-tile') !== 0) {
            $result['classes'][] = 'layout-' . $layout;
        }
        if (($breakpoint = trim((string)($data['tx_pizpalue_layout_breakpoint'] ?? ''))) !== '') {
            $result['classes'][] = 'pp-layout-' . $breakpoint;
        }
        if (($data['frame_class'] ?? '') !== 'none') {
            if (($innerSpaceClass = $data['tx_pizpalue_inner_space_before_class'] ?? '') !== '') {
                $result['classes'][] = 'pp-inner-space-before-' . $innerSpaceClass;
            }
            if (($innerSpaceClass = $data['tx_pizpalue_inner_space_after_class'] ?? '') !== '') {
                $result['classes'][] = 'pp-inner-space-after-' . $innerSpaceClass;
            }
        }
        if ((bool)($data['background_image'] ?? false)) {
            $result['classes'][] = 'pp-has-backgroundimage';
        }
        if (($classes = trim((string)($data['tx_pizpalue_classes'] ?? ''))) !== '') {
            $result['classes'] = array_merge(
                $result['classes'],
                GeneralUtility::trimExplode(' ', $classes, true)
            );
        }
    }

    private function addInnerClasses(array $data, array &$result): void
    {
        if (($classes = trim((string)($data['tx_pizpalue_inner_classes'] ?? ''))) !== '') {
            $result['innerClasses'] = GeneralUtility::trimExplode(' ', $classes, true);
        }
    }

    private function addFramelessClasses(array $data, array &$result): void
    {
        if (($data['frame_class'] ?? '') !== 'none') {
            return;
        }
        if (($class = trim((string)($data['space_before_class'] ?? ''))) !== '') {
            $result['classes'][] = 'pp-space-before';
            $result['classes'][] = 'pp-space-before-' . $class;
        }
        if (($class = trim((string)($data['space_after_class'] ?? ''))) !== '') {
            $result['classes'][] = 'pp-space-after';
            $result['classes'][] = 'pp-space-after-' . $class;
        }
        if (
            ($class = trim((string)($data['background_color_class'] ?? 'none'))) !== 'none' &&
            $class !== ''
        ) {
            $result['classes'][] = 'pp-background-color';
            $result['classes'][] = 'pp-background-color-' . $class;
        }
        if ($result['classes'] !== []) {
            $result['classes'] = array_unique(array_merge(
                ['pp-frameless-content', 'pp-type-' . $data['CType']],
                $result['classes']
            ));
        }
    }

    private function addStylesAndAttributes(array $data, array &$result): void
    {
        $uid = (int) $data['uid'];
        $result['styles'] = [];
        $styles = trim($data['tx_pizpalue_style'] ?? '');
        if ((bool)$styles) {
            if (str_contains($styles, '{')) {
                // Add styles to asset collector
                $css = trim(str_replace('#self', '#c' . $uid, $styles));
                $this->assetCollector->addInlineStyleSheet('ppCe' . $uid, $css, [], ['priority' => true]);
                $result['styles'] = [];
            } else {
                // Add styles inline
                foreach (GeneralUtility::trimExplode(';', $styles, true) as $style) {
                    $parts = GeneralUtility::trimExplode(':', $style, true);
                    $result['styles'][] = $parts[0] . ': ' . $parts[1];
                }
            }
        }
        $result['attributes'] = $this->getAttributes($data['tx_pizpalue_attributes'] ?? '');
    }

    private function addAnimation(
        array $data,
        array $pizpalueConstants,
        array &$result
    ): void {
        if (!(bool)($data['tx_pizpalue_animation'] ?? false)) {
            return;
        }
        if (!is_array($config = $pizpalueConstants['animation'][$data['tx_pizpalue_animation']] ?? false)) {
            return;
        }
        if (($classes = trim((string)($config['classes'] ?? ''))) !== '') {
            $classes = GeneralUtility::trimExplode(' ', $classes, true);
            $result['classes'] = array_merge($result['classes'], $classes);
        }
        if (($style = trim((string)($config['styles'] ?? ''))) !== '') {
            $styles = GeneralUtility::trimExplode(';', $style, true);
            $result['styles'] = array_merge($result['styles'], $styles);
        }
        if (
            ($attributes = trim((string)($config['attributes'] ?? ''))) !== '' &&
            ($attributes = $this->getAttributes($attributes)) !== []
        ) {
            $result['attributes'] = array_merge($result['attributes'], $attributes);
        }
    }

    private function addTiles(array $data, array &$result): void
    {
        if (
            ($layout = trim((string)($data['layout'] ?? ''))) !== '' &&
            str_contains($layout, 'pp-tile')
        ) {
            $result['classes'][] = 'pp-tile';
            $result['classes'][] = $layout;
            $result['isTile'] = true;
        }
    }

    private function addJoshAssets(
        array $pizpalueConstants,
        array &$result
    ): void {
        $attributes = implode(' ', $result['attributes']);
        if (!str_contains($attributes, 'data-josh')) {
            return;
        }
        $result['hasScrollAnimation'] = true;
        $result['classes'][] = 'josh-js';
        $this->addAnimateCssToAssetCollector();
        $this->assetHelper->includeJosh();
        $this->assetCollector->addInlineJavaScript('ppJoshInit', sprintf(
            ';+function () { const josh = new Josh({ %s }); }();',
            $pizpalueConstants['animation']['josh']['initParams'] ?? ''
        ));
    }

    private function addTwikitoAssets(
        array &$result
    ): void {
        $attributes = implode(' ', $result['attributes']);
        if (!str_contains($attributes, 'data-scroll')) {
            return;
        }
        $result['hasScrollAnimation'] = true;
        // Ensure animate properties are complete (contain animate__animated)
        if (
            str_contains($attributes, 'animate__') &&
            !str_contains($attributes, 'animate__animated')
        ) {
            foreach ($result['attributes'] as &$attribute) {
                if (
                    str_contains($attribute, 'animate__') &&
                    !str_contains($attribute, 'animate__animated')
                ) {
                    $attribute = str_replace('animate__', 'animate__animated animate__', $attribute);
                    break;
                }
            }
            unset($attribute);
        }
        if (!str_contains($attributes, 'data-scroll-reverse')) {
            $result['attributes'][] = 'data-scroll-reverse="true"';
        }
        $this->addAnimateCssToAssetCollector();
        $this->assetHelper->includeTwikito();
        $this->assetCollector->addInlineStyleSheet(
            'twikitoOnscroll',
            '[data-scroll].is-outside { transition: none; animation: none; }',
            [],
            ['priority' => true]
        );
    }

    private function addAnimateCssAssets(
        array &$result
    ): void {
        $classes = implode(' ', $result['classes']);
        if (!str_contains($classes, 'animate__')) {
            return;
        }
        $result['hasCssAnimation'] = true;
        // Ensure animate properties are complete (contain animate__animated)
        if (!str_contains($classes, 'animate__animated')) {
            $result['classes'][] = 'animate__animated';
        }
        $this->addAnimateCssToAssetCollector();
    }
}
