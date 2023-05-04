<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers;

use Buepro\Pizpalue\Helper\AssetHelper;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * FrameDataViewHelper.
 *
 * Compiles pizpalue frame data.
 *
 * Usage:
 *    `{pp:frameData(data: data, pizpalueConstants: pizpalue, as: 'ppData')}`
 *
 * As-data is an array containing the following fields:
 *    - classes: Array of classes
 *    - innerClasses: Array of classes
 *    - styles: Array of style definitions
 *    - attributes: Array of attributes
 *    - isTile: Boolean, true if content element is a tile
 *    - hasCssAnimation: Boolean, indication the presence from a css animation
 *    - hasScrollAnimation: Boolean, indicating the presence from a scroll animation
 *    - optimizeLinkTargets: Boolean, passes through the constant value from `pizpalue.seo.optimizeLinkTargets`
 *
 * @deprecated since 13.0.2
 * @todo Refactor to service class
 */
class FrameDataViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    private static AssetHelper $assetHelper;
    private static AssetCollector $assetCollector;

    /**
     * Initialize arguments.
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('data', 'array', 'Content element data', true);
        $this->registerArgument('pizpalueConstants', 'array', 'Pizpalue constants', true);
        $this->registerArgument('as', 'string', 'Name of variable to create', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        self::$assetHelper = GeneralUtility::makeInstance(AssetHelper::class);
        self::$assetCollector = GeneralUtility::makeInstance(AssetCollector::class);
        $data = $arguments['data'];
        $pizpalueConstants = $arguments['pizpalueConstants'] ?? [];
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
        self::addClasses($data, $result);
        self::addInnerClasses($data, $result);
        self::addStylesAndAttributes($data, $result);
        self::addAnimation($data, $pizpalueConstants, $result);
        self::addTiles($data, $result);
        self::addJoshAssets($pizpalueConstants, $result);
        self::addTwikitoAssets($result);
        self::addAnimateCssAssets($result);
        self::addFramelessClasses($data, $result);
        $variableProvider = $renderingContext->getVariableProvider();
        if ($arguments['as']) {
            $variableProvider->add($arguments['as'], $result);
            return '';
        }
        return $result;
    }

    private static function getAttributes(string $attributes): array
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

    private static function addAnimateCssToAssetCollector(): void
    {
        self::$assetHelper->includeAnimateCss();
    }

    protected static function addClasses(array $data, array &$result): void
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

    protected static function addInnerClasses(array $data, array &$result): void
    {
        if (($classes = trim((string)($data['tx_pizpalue_inner_classes'] ?? ''))) !== '') {
            $result['innerClasses'] = GeneralUtility::trimExplode(' ', $classes, true);
        }
    }

    protected static function addFramelessClasses(array $data, array &$result): void
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

    protected static function addStylesAndAttributes(array $data, array &$result): void
    {
        $uid = (int) $data['uid'];
        $result['styles'] = [];
        $styles = trim($data['tx_pizpalue_style'] ?? '');
        if ((bool)$styles) {
            if (strpos($styles, '{') !== false) {
                // Add styles to asset collector
                $css = trim(str_replace('#self', '#c' . $uid, $styles));
                self::$assetCollector->addInlineStyleSheet('ppCe' . $uid, $css, [], ['priority' => true]);
                $result['styles'] = [];
            } else {
                // Add styles inline
                foreach (GeneralUtility::trimExplode(';', $styles, true) as $style) {
                    $parts = GeneralUtility::trimExplode(':', $style, true);
                    $result['styles'][] = $parts[0] . ': ' . $parts[1];
                }
            }
        }
        $result['attributes'] = self::getAttributes($data['tx_pizpalue_attributes']);
    }

    protected static function addAnimation(
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
            ($attributes = self::getAttributes($attributes)) !== []
        ) {
            $result['attributes'] = array_merge($result['attributes'], $attributes);
        }
    }

    protected static function addTiles(array $data, array &$result): void
    {
        if (
            ($layout = trim((string)($data['layout'] ?? ''))) !== '' &&
            strpos($layout, 'pp-tile') !== false
        ) {
            $result['classes'][] = 'pp-tile';
            $result['classes'][] = $layout;
            $result['isTile'] = true;
        }
    }

    protected static function addJoshAssets(
        array $pizpalueConstants,
        array &$result
    ): void {
        $attributes = implode(' ', $result['attributes']);
        if (strpos($attributes, 'data-josh') === false) {
            return;
        }
        $result['hasScrollAnimation'] = true;
        $result['classes'][] = 'josh-js';
        self::addAnimateCssToAssetCollector();
        self::$assetHelper->includeJosh();
        self::$assetCollector->addInlineJavaScript('ppJoshInit', sprintf(
            ';+function () { const josh = new Josh({ %s }); }();',
            $pizpalueConstants['animation']['josh']['initParams'] ?? ''
        ));
    }

    protected static function addTwikitoAssets(
        array &$result
    ): void {
        $attributes = implode(' ', $result['attributes']);
        if (strpos($attributes, 'data-scroll') === false) {
            return;
        }
        $result['hasScrollAnimation'] = true;
        // Ensure animate properties are complete (contain animate__animated)
        if (
            strpos($attributes, 'animate__') !== false &&
            strpos($attributes, 'animate__animated') === false
        ) {
            foreach ($result['attributes'] as &$attribute) {
                if (
                    strpos($attribute, 'animate__') !== false &&
                    strpos($attribute, 'animate__animated') === false
                ) {
                    $attribute = str_replace('animate__', 'animate__animated animate__', $attribute);
                    break;
                }
            }
            unset($attribute);
        }
        if (strpos($attributes, 'data-scroll-reverse') === false) {
            $result['attributes'][] = 'data-scroll-reverse="true"';
        }
        self::addAnimateCssToAssetCollector();
        self::$assetHelper->includeTwikito();
        self::$assetCollector->addInlineStyleSheet(
            'twikitoOnscroll',
            '[data-scroll].is-outside { transition: none; animation: none; }',
            [],
            ['priority' => true]
        );
    }

    protected static function addAnimateCssAssets(
        array &$result
    ): void {
        $classes = implode(' ', $result['classes']);
        if (strpos($classes, 'animate__') === false) {
            return;
        }
        $result['hasCssAnimation'] = true;
        // Ensure animate properties are complete (contain animate__animated)
        if (strpos($classes, 'animate__animated') === false) {
            $result['classes'][] = 'animate__animated';
        }
        self::addAnimateCssToAssetCollector();
    }
}
