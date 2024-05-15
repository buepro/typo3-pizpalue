<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\DataProcessing;

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class PosterProcessor implements DataProcessorInterface
{

    public function __construct(
        private readonly FileRepository $fileRepository,
    ) {
    }

    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        if (count($processedData['files'] ?? []) < 1) {
            return $processedData;
        }
        /** @var FileReference $fileReference */
        foreach ($processedData['files'] as $key => $fileReference) {
            $fileReference = \Buepro\Pizpalue\Sysext\Core\Resource\FileReference::getFromCoreFileReference($fileReference);
            $posterReferences = $this->fileRepository->findByRelation(
                'sys_file_reference',
                'tx_pizpalue_poster',
                $fileReference->getUid()
            );
            if ($posterReferences[0] ?? false) {
                $fileReference->setPoster($posterReferences[0]);
                $processedData['files'][$key] = $fileReference;
            }
        }
        return $processedData;
    }
}
