<?php

declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ViewHelpers;

use TYPO3\CMS\Fluid\ViewHelpers\Format\AbstractEncodingViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * Class DesarmAttributeViewHelper
 *
 * The class is used to desarm (or sanitize) attribute definitions. It is escaping the attribute name and values and
 * just allows class, style and data attributes to pass through.
 *
 * By desarming the attribute values they are trimmed as well.
 *
 * This class has been inspired by HtmlentitiesDecodeViewHelper.
 *
 * Usage:
 * ---
 * <pp:desarmAttribute keepQuotes="false" encoding="ISO-8859-1">
 *    class="test-class" data-test="data-test-config"
 * </pp:desarmAttribute>
 * ---
 * <pp:desarmAttribute
 *    value='class="test-class" data-test="data-test-config"' keepQuotes="false" encoding="ISO-8859-1" />
 * ---
 * {attribute -> pp:desarmAttribute()}
 * ---
 *
 */
class DesarmAttributeViewHelper extends AbstractEncodingViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    /**
     * We accept value and children interchangeably, thus we must disable children escaping.
     *
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * Avoid escaping quotes enclosing the attribute value (attribute-name="attribute-value").
     * The attribute name and value will be escaped separately (see desarmAttributes).
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize ViewHelper arguments
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('value', 'string', 'String to desarm');
        $this->registerArgument(
            'keepQuotes',
            'bool',
            'If TRUE, single and double quotes won\'t be replaced (sets ENT_NOQUOTES flag).',
            false,
            false
        );
        $this->registerArgument(
            'encoding',
            'string',
            'Used charset in encoding (e.g. ISO-8859-1)',
            false,
            ''
        );
    }

    /**
     * Derives the available attribute declarations from a string and desarms the attribute names as well as the
     * attribute values.
     *
     * Just class, style and data attributes are allowed.
     *
     * @param string $value Attribute definitions
     * @param bool $keepQuotes
     * @param string $encoding
     * @return string Desarmed attribute definitions
     */
    private static function desarmAttributes(string $value, bool $keepQuotes, string $encoding): string
    {
        // Init
        $htmlEntityFlags = $keepQuotes ? ENT_NOQUOTES : ENT_COMPAT;

        // In case some values come from inline notation (escapes values)
        $value = html_entity_decode($value, $htmlEntityFlags, $encoding);

        // Remove tabs, line breaks, excessive spaces
        $value = preg_replace('/[\t\r\n]\s+|\s+/', ' ', $value);
        if ($value === null) {
            return '';
        }

        // Get attributes
        $result = preg_match_all('/([\w\-]*)\s*=\s*\"([^\"]*)\"/', $value, $attributes);
        if (!(bool)$result) {
            return '';
        }

        // Desarm attribute names and values
        $desarmedKey = [];
        $desarmedValue = [];
        if (isset($attributes[2])) {
            foreach ($attributes[1] as $attrKey) {
                $desarmedKey[] = htmlentities($attrKey, $htmlEntityFlags, $encoding);
            }
            foreach ($attributes[2] as $attrValue) {
                $desarmedValue[] = trim(htmlentities($attrValue, $htmlEntityFlags, $encoding));
            }
        }

        // Compile desarmed attributes
        $desarmedAttr = [];
        foreach ($attributes[1] as $key => $attrName) {
            // Just allow class, style and data attributes
            if ($attrName === 'class' || $attrName === 'style' || strpos($attrName, 'data') === 0) {
                $desarmedAttr[] = $desarmedKey[$key] . '="' . $desarmedValue[$key] . '"';
            }
        }
        return implode(' ', $desarmedAttr);
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        /** @var array{encoding: ?string, keepQuotes: bool} $arguments */
        $value = $renderChildrenClosure();
        $encoding = (string) $arguments['encoding'];
        $keepQuotes = $arguments['keepQuotes'];

        if (!is_string($value)) {
            return '';
        }

        if ($encoding === '') {
            $encoding = self::resolveDefaultEncoding();
        }
        return self::desarmAttributes($value, $keepQuotes, $encoding);
    }
}
