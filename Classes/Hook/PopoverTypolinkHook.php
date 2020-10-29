<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Hook;

use TYPO3\CMS\Core\Html\HtmlParser;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Class PopoverTypolinkHook
 *
 * Hint: Is used from FE.
 *
 */
class PopoverTypolinkHook
{
    /**
     * Adds popover related attributes to the a tag. Attributes defined here are overwritten by already existing
     * attributes.
     *
     * In case the `data-html` attribute is absent or is set to true the `data-content` attribute is obtained by
     * parsing the content with the configuration from `lib.parseFunc_RTE`.
     *
     * @param array $params
     * @param ContentObjectRenderer $ref The calling ContentObjectRenderer
     */
    public function postProcess(&$params, &$ref)
    {
        if (isset($params['linkDetails']['type'], $params['finalTag']) && $params['linkDetails']['type'] === 'pppopover') {
            /** @var HtmlParser $htmlParser */
            $htmlParser = GeneralUtility::makeInstance(HtmlParser::class);
            // Get current attributes
            [$attributes, $attributesMetadata] = $htmlParser->get_tag_attributes($params['finalTag'], true);
            // Define popover attributes
            $popoverAttributes = [
                'tabindex' => '0',
                'data-toggle' => 'popover',
                'data-html' => 'true',
            ];
            if (isset($params['linkDetails']['content'])) {
                $content = $params['linkDetails']['content'];
                if (isset($attributes['data-html']) && $attributes['data-html'] === 'false') {
                    $popoverAttributes['data-content'] = strip_tags($content);
                } else {
                    /** @var ContentObjectRenderer $cObjRenderer */
                    $cObjRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
                    $popoverAttributes['data-content'] = htmlspecialchars($cObjRenderer->parseFunc($content, [], '< lib.parseFunc_RTE'));
                }
            }
            // Create tag
            $finalAttributes = array_merge($popoverAttributes, $attributes);
            $params['finalTag'] = '<a ' . $htmlParser->compileTagAttribs($finalAttributes, $attributesMetadata) . '>';
        }
    }
}
