<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers;

use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * FrameDataViewHelper.
 *
 * Compiles pizpalue related attribute data.
 *
 * Usage:
 *    `{pp:pizpalueFrame(data: data, pizpalueConstants: pizpalue, as: 'ppData')}`
 *
 * As-data is an array containing the following fields:
 *    - classes: Array of classes
 *    - styles: Array of style definitions
 *    - attributes: Array of attributes
 *    - isTile: Boolean, true if content element is a tile
 *    - hasCssAnimation: Boolean, indication the presence from a css animation
 *    - hasScrollAnimation: Boolean, indicating the presence from a scroll animation
 *    - optimizeLinkTargets: Boolean, passes through the constant value from `pizpalue.seo.optimizeLinkTargets`
 */
class PizpalueFrameViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

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
        $assetCollector = GeneralUtility::makeInstance(AssetCollector::class);
        $data = $arguments['data'];
        $pizpalueConstants = $arguments['pizpalueConstants'] ?? [];
        $result = [
            'classes' => [],
            'styles' => [],
            'attributes' => [],
            'isTile' => false,
            'hasCssAnimation' => false,
            'hasScrollAnimation' => false,
            'optimizeLinkTargets' => (bool) ($pizpalueConstants['seo']['optimizeLinkTargets'] ?? true),
        ];
        self::addClasses($assetCollector, $data, $result);
        self::addStylesAndAttributes($assetCollector, $data, $result);
        self::addAnimation($assetCollector, $data, $pizpalueConstants, $result);
        self::addTiles($assetCollector, $data, $result);
        self::addAosAssets($assetCollector, $data, $pizpalueConstants, $result);
        self::addJoshAssets($assetCollector, $data, $pizpalueConstants, $result);
        self::addTwikitoAssets($assetCollector, $data, $pizpalueConstants, $result);
        self::addAnimateCssAssets($assetCollector, $data, $pizpalueConstants, $result);
        self::addDependantClasses($assetCollector, $data, $result);
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

    private static function addAnimateCssToAssetCollector(AssetCollector $assetCollector): void
    {
        /** @extensionScannerIgnoreLine */
        $assetCollector->addStyleSheet('ppAnimateCss', 'EXT:pizpalue/Resources/Public/Contrib/animate.css/animate.min.css');
    }

    protected static function addClasses(AssetCollector $assetCollector, array $data, array &$result): void
    {
        if (($layout = trim((string)($data['layout'] ?? '0'))) !== '0' && strpos($layout, 'pp-tile') !== 0) {
            $result['classes'][] = 'layout-' . $layout;
        }
        if (($breakpoint = trim((string)($data['tx_pizpalue_layout_breakpoint'] ?? ''))) !== '') {
            $result['classes'][] = 'pp-layout-' . $breakpoint;
        }
        if (($classes = trim((string)($data['tx_pizpalue_classes'] ?? ''))) !== '') {
            $result['classes'] = array_merge(
                $result['classes'],
                GeneralUtility::trimExplode(' ', $classes, true)
            );
        }
    }

    protected static function addDependantClasses(AssetCollector $assetCollector, array $data, array &$result): void
    {
        $spaceBefore = trim((string)($data['space_before_class'] ?? ''));
        $spaceAfter = trim((string)($data['space_after_class'] ?? ''));
        if (
            isset($data['frame_class']) && $data['frame_class'] === 'none' &&
            (
                count($result['classes']) > 0 || count($result['styles']) > 0 || count($result['attributes']) > 0 ||
                $spaceBefore !== '' || $spaceAfter !== ''
            )
        ) {
            $spaceBefore = $spaceBefore !== '' ? $spaceBefore : 'none';
            $spaceAfter = $spaceAfter !== '' ? $spaceAfter : 'none';
            $result['classes'] = array_merge(
                ['pp-content', 'pp-type-' . $data['CType']],
                $result['classes'],
                ['pp-space-before-' . $spaceBefore, 'pp-space-after-' . $spaceAfter]
            );
        }
    }

    protected static function addStylesAndAttributes(AssetCollector $assetCollector, array $data, array &$result): void
    {
        $uid = (int) $data['uid'];
        $result['styles'] = [];
        $styles = trim($data['tx_pizpalue_style'] ?? '');
        if ((bool)$styles) {
            if (strpos($styles, '{') !== false) {
                // Add styles to asset collector
                $css = trim(str_replace('#self', '#c' . $uid, $styles));
                $assetCollector->addInlineStyleSheet('ppCe' . $uid, $css, [], ['priority' => true]);
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
        AssetCollector $assetCollector,
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

    protected static function addTiles(AssetCollector $assetCollector, array $data, array &$result): void
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

    protected static function addAosAssets(
        AssetCollector $assetCollector,
        array $data,
        array $pizpalueConstants,
        array &$result
    ): void {
        $attributes = implode(' ', $result['attributes']);
        if (strpos($attributes, 'data-aos') === false) {
            return;
        }
        $result['hasScrollAnimation'] = true;
        /** @extensionScannerIgnoreLine */
        $assetCollector->addStyleSheet(
            'ppAos',
            'EXT:pizpalue/Resources/Public/Contrib/aos/aos.css'
        );
        $assetCollector->addJavaScript(
            'ppAos',
            'EXT:pizpalue/Resources/Public/Contrib/aos/aos.js'
        );
        $assetCollector->addInlineJavaScript('ppAosInit', sprintf(
            "AOS.init({ %s }); $('[data-aos]').parent().css('overflow', 'hidden');",
            $pizpalueConstants['animation']['aos']['initParams'] ?? ''
        ));
    }

    /**
     * @deprecated since v12, will be removed in v14
     */
    private static function substituteJoshDataDelayAttribute(array $attributes): array
    {
        if (str_contains(($attributesString = implode(':pp:', $attributes)), 'data-josh-delay')) {
            $attributes = explode(
                ':pp:',
                str_replace('data-josh-delay', 'data-josh-anim-delay', $attributesString)
            );
        }
        return $attributes;
    }

    protected static function addJoshAssets(
        AssetCollector $assetCollector,
        array $data,
        array $pizpalueConstants,
        array &$result
    ): void {
        $attributes = implode(' ', $result['attributes']);
        if (strpos($attributes, 'data-josh') === false) {
            return;
        }
        // @phpstan-ignore-next-line
        $result['attributes'] = self::substituteJoshDataDelayAttribute($result['attributes']);
        $result['hasScrollAnimation'] = true;
        $result['classes'][] = 'josh-js';
        self::addAnimateCssToAssetCollector($assetCollector);
        $assetCollector->addJavaScript(
            'ppJosh',
            'EXT:pizpalue/Resources/Public/Contrib/josh.js/dist/josh.pp.min.js'
        );
        $assetCollector->addInlineJavaScript('ppJoshInit', sprintf(
            ';+function () { const josh = new Josh({ %s }); }();',
            $pizpalueConstants['animation']['josh']['initParams'] ?? ''
        ));
    }

    protected static function addTwikitoAssets(
        AssetCollector $assetCollector,
        array $data,
        array $pizpalueConstants,
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
        self::addAnimateCssToAssetCollector($assetCollector);
        $assetCollector->addJavaScript(
            'ppTwikitoOnscroll',
            'EXT:pizpalue/Resources/Public/Contrib/Twikito/onscroll-effect/dist/onscroll-effect.min.js'
        );
        $assetCollector->addInlineStyleSheet(
            'twikitoOnscroll',
            '[data-scroll].is-outside { transition: none; animation: none; }',
            [],
            ['priority' => true]
        );
    }

    protected static function addAnimateCssAssets(
        AssetCollector $assetCollector,
        array $data,
        array $pizpalueConstants,
        array &$result
    ): void {
        $classes = implode(' ', $result['classes']);
        if (strpos($classes, 'animate__') === false) {
            return;
        }
        $result['hasCssAnimation'] = true;
        // Ensure animate properties are complete (contain animate__animated)
        if (
            strpos($classes, 'animate__') !== false &&
            strpos($classes, 'animate__animated') === false
        ) {
            $result['classes'][] = 'animate__animated';
        }
        self::addAnimateCssToAssetCollector($assetCollector);
    }
}
