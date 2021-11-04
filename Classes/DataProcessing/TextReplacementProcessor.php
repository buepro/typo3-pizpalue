<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\DataProcessing;

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * This processor inserts TS-constant values, getText data type values or values from the current `processedData` array.
 * In case the value from the `processedData` array is an instance of `FileReference` the url to the file is inserted.
 *
 * **Example:**
 * '{$myext.name}, {data.field.teaser} {processedData.files.0}' would become
 * 'Roman, see image under http://domain.ch/path/to/image/image.jpg'
 */
class TextReplacementProcessor implements DataProcessorInterface
{
    /**
     * @var ContentObjectRenderer $cObj
     */
    private $cObj;

    /**
     * @var array $processedData;
     */
    private $processedData;

    /**
     * Process content object data
     *
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        // Init
        $this->cObj = $cObj;
        $this->processedData = $processedData;
        $fieldName = 'bodytext';
        if (isset($processorConfiguration['references.']) && !empty($processorConfiguration['references.'])) {
            $referenceConfiguration = $processorConfiguration['references.'];
            $fieldName = $cObj->stdWrapValue('fieldName', $referenceConfiguration);
        }

        // Process
        $text = $processedData['data'][$fieldName];
        $text = $this->replaceProcessedData($text, $processedData, $cObj);
        $text = $this->replaceData($text, $cObj);
        $text = $this->replaceParentData($text, $cObj);
        $text = $this->replaceConstants($text);
        $text = $this->replaceFunction($text);
        $processedData['data'][$fieldName] = $text;
        return $processedData;
    }

    /**
     * Replaces text parts defined in the form `{$constant.name}` with their related constant values.
     *
     * **Example:**
     * In case a TS constant definition `myext.name = Roman` exists 'Hi {$myext.name}' would become 'Hi Roman'.
     *
     * @param string $text
     * @return string
     */
    private function replaceConstants(string $text): string
    {
        // Get constants
        if ($GLOBALS['TSFE']->tmpl->flatSetup === null
            || !is_array($GLOBALS['TSFE']->tmpl->flatSetup ?? false)
            || count($GLOBALS['TSFE']->tmpl->flatSetup) === 0) {
            $GLOBALS['TSFE']->tmpl->generateConfig();
        }
        $constants = $GLOBALS['TSFE']->tmpl->flatSetup;

        // Replace constants
        if (preg_match_all('/{\$([\w.\-]+)}/', $text, $matches)) {
            $replacements = [];
            $patterns = [];
            foreach ($matches[1] as $key => $constantName) {
                $patterns[] = '/{\$' . $constantName . '}/';
                if (isset($constants[$constantName])) {
                    $replacements[] = $constants[$constantName];
                } else {
                    $replacements[] = $matches[0][$key];
                }
            }
            $text = preg_replace($patterns, $replacements, $text);
        }
        return $text;
    }

    /**
     * Replaces text parts defined in the form `{data:getText}`.
     * The `getText` part is supplied to the getData-function (evaluating the `getText` data type).
     * When using the key `field` data from the current content record (`tt_content`) is obtained.
     *
     * **Example:**
     * In case the `teaser` field from current content record contains 'This is the teaser content'
     * 'Text: {data:field:teaser}' would become 'Text: This is the teaser content'.
     *
     * @param string $text
     * @param ContentObjectRenderer $cObj
     * @return string
     */
    private function replaceData(string $text, ContentObjectRenderer $cObj): string
    {
        if (preg_match_all('/{data:([\w.\-:]+)}/', $text, $matches)) {
            $replacements = [];
            $patterns = [];
            foreach ($matches[1] as $key => $instruction) {
                $patterns[] = '/{data:' . $instruction . '}/';
                $replacements[] = $cObj->getData($instruction, $cObj->data);
            }
            $text = preg_replace($patterns, $replacements, $text);
        }
        return $text;
    }

    /**
     * Replaces text parts defined in the form `{parentData:getText}`.
     * Works like method getData except that it retrieves data from parent record.
     *
     * @param string $text
     * @param ContentObjectRenderer $cObj
     * @return string
     */
    private function replaceParentData(string $text, ContentObjectRenderer $cObj): string
    {
        if (preg_match_all('/{parentData:([\w.\-:]+)}/', $text, $matches)) {
            $replacements = [];
            $patterns = [];
            foreach ($matches[1] as $key => $instruction) {
                $patterns[] = '/{parentData:' . $instruction . '}/';
                $replacements[] = $cObj->getData($instruction, $cObj->parentRecord->data);
            }
            $text = preg_replace($patterns, $replacements, $text);
        }
        return $text;
    }

    /**
     * Replaces text parts defined in the form `{processedData:array.path}`.
     * The text is obtained from the `processedData` array. In case the obtained value is an instance of `FileReference`
     * the url to the file is inserted. In case the array path is `breadcrumb` a breadcrumb markup is obtained (it is
     * assumed that data is obtained by `MenuProcessor`).
     *
     * **Example:**
     * In case $processedData[schemaImage][0] is an instance of `FileReference` '{proseccedData:schemaImage.0}' becomes
     * 'https://domain.ch/path/to/image/image.jpg'.
     *
     * @param string $text
     * @param array $processedData
     * @param ContentObjectRenderer $cObj
     * @return string
     */
    private function replaceProcessedData(string $text, array $processedData, ContentObjectRenderer $cObj): string
    {
        if (preg_match_all('/{processedData:([\w.\-:]+)}/', $text, $matches)) {
            $replacements = [];
            $patterns = [];
            foreach ($matches[1] as $key => $path) {
                $patterns[] = '/{processedData:' . $path . '}/';
                $parts = explode('.', $path);
                $value = $processedData;
                foreach ($parts as $part) {
                    $value = $value[$part];
                }
                if ($value) {
                    if ($value instanceof FileReference) {
                        $config = [
                            'width' => $processedData['data']['pi_flexform']['image_width']
                        ];
                        $urlPrefix = GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST');
                        $imageResource = $cObj->getImgResource($value, $config);
                        $replacements[] = $urlPrefix . '/' . $imageResource[3];
                    } elseif ($parts[0] === 'breadcrumb') {
                        $markup = $this->generateBreadcrumbMarkup($value);
                        $replacements[] = json_encode($markup, JSON_UNESCAPED_SLASHES);
                    } else {
                        $replacements[] = $value;
                    }
                } else {
                    $replacements[] = '{processedData:' . $path . '}';
                }
            }
            $text = preg_replace($patterns, $replacements, $text);
        }
        return $text;
    }

    /**
     * Encodes every character from $str to its entity.
     * Might be used for emails to not reveal them at first.
     *
     * @param string $str
     * @return string
     */
    private function entityEncodeChars(string $str)
    {
        $converted = mb_convert_encoding($str, 'UTF-32', 'UTF-8');
        $t = unpack('N*', $converted);
        $t = array_map(function ($n) {
            return "&#$n;";
        }, $t);
        $encoded = implode('', $t);
        if (html_entity_decode($encoded) === $str) {
            return $encoded;
        }
        return $str;
    }

    /**
     * Converts line breaks to the character sequence '\r\n'.
     * Is of interest for text properties.
     *
     * @param string $text
     * @return string|string[]
     */
    private function newLineToRn(string $text)
    {
        return str_replace([chr(13) . chr(10), chr(10)], '\r\n', $text);
    }

    /**
     * @param string $text
     * @return string
     */
    private function stripTags(string $text): string
    {
        return strip_tags($text);
    }

    /**
     * Creates the breadcrumb markup.
     * Is based on extension brotkrueml/sdbreadcrumb.
     *
     * @param array $breadcrumb
     * @return array
     */
    private function generateBreadcrumbMarkup(array $breadcrumb): array
    {
        $siteUrl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
        if ($breadcrumb[0]['link'][0] === '/') {
            $siteUrl = rtrim($siteUrl, '/');
        }

        $markup = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [],
        ];

        foreach ($breadcrumb as $index => $item) {
            $markup['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => [
                    '@type' => 'WebPage',
                    '@id' => $siteUrl . $item['link'],
                    'name' => $item['title'],
                ],
            ];
        }

        return $markup;
    }

    /**
     * Replaces text parts defined in the form `{func:someAvailableFunction:argument}` with the value obtained by
     * passing `argument` to `someAvailableFunction`. The following functions are available: `entityEncodeChars`.
     *
     * @param string $text
     * @return string
     */
    private function replaceFunction(string $text): string
    {
        if (preg_match_all('/{func:([\s\S\r\n][^}]+)}/', $text, $matches)) {
            $replacements = [];
            $patterns = [];
            foreach ($matches[1] as $key => $funcStatement) {
                $patterns[] = '/{func:' . preg_quote($funcStatement, '/') . '}/';
                $parts = GeneralUtility::trimExplode(':', $funcStatement, false, 2);
                if (method_exists($this, $parts[0])) {
                    if (count($parts) === 2) {
                        $replacements[] = $this->{$parts[0]}($parts[1]);
                    } else {
                        $replacements[] = $this->{$parts[0]}();
                    }
                } else {
                    $replacements[] = '{func:' . $funcStatement . '}';
                }
            }
            $text = preg_replace($patterns, $replacements, $text);
        }
        return $text;
    }
}
