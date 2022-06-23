<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\PersistService;

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LogoService extends AbstractService
{
    public function process(): void
    {
        $logoWidth = $logoHeight = '';
        foreach (['logo_file_reference', 'logo_file_inverted_reference'] as $field) {
            if (($uid = (int)$this->formFields[$field]) > 0) {
                $fileRepository = GeneralUtility::makeInstance(FileRepository::class);
                $fileReference = $fileRepository->findFileReferenceByUid($uid);
                $fileProperties = $this->getFileProperties($fileReference);
                $propertyName = GeneralUtility::underscoredToLowerCamelCase(substr(substr($field, 5), 0, -9));
                $this->typoScriptMapper->bufferProperty('page.logo.' . $propertyName, ltrim($fileProperties['file'], " \t\n\r\0\x0B/"));
                $logoWidth = ($fileProperties['width'] ?? '') !== '' ? $fileProperties['width'] : $logoWidth;
                $logoHeight = ($fileProperties['height'] ?? '') !== '' ? $fileProperties['height'] : $logoHeight;
            }
        }
        if ($logoWidth !== '') {
            $this->typoScriptMapper->bufferProperty('page.logo.width', (string)$logoWidth);
        }
        if ($logoHeight !== '') {
            $this->typoScriptMapper->bufferProperty('page.logo.height', (string)$logoHeight);
        }
    }

    protected function getFileProperties(FileReference $fileReference): array
    {
        $file = $fileReference->getOriginalFile();
        $properties = $file->getProperties();
        return [
            'file' => $file->getPublicUrl(),
            'width' => $properties['width'] ?? '',
            'height' => $properties['height'] ?? '',
        ];
    }
}
