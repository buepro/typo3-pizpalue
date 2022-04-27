<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\PersistService;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Service\Archive\ZipService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AppiconService extends AbstractService
{
    public function process(): void
    {
        if (
            (int)$this->configurationRecord['appicon_generator_archive'] > 0 &&
            strpos((string)$this->configurationRecord['appicon_generator_text'], '<link') !== false
        ) {
            $fileRepository = GeneralUtility::makeInstance(FileRepository::class);
            /** @var FileReference $fileReference */
            [$fileReference] = $fileRepository->findByRelation(
                'tx_easyconf_configuration',
                'appicon_generator_archive',
                $this->configurationRecord['uid']
            );
            $this->unzipAndCopy($fileReference->getOriginalFile()->getForLocalProcessing(), Environment::getPublicPath());
            $this->typoScriptMapper->bufferProperty(
                'pizpalue.appIcon.headerData',
                str_replace("\r\n", '', $this->configurationRecord['appicon_generator_text'])
            );
            $this->typoScriptMapper->bufferProperty('page.favicon.file', 'AppIcon');
        }
    }

    protected function unzipAndCopy(string $zipFile, string $targetPath): void
    {
        try {
            $zipService = GeneralUtility::makeInstance(ZipService::class);
            if (file_exists($zipFile) && $zipService->verify($zipFile)) {
                $zipDirectory = GeneralUtility::dirname($zipFile) . '/appicon-' . time();
                GeneralUtility::mkdir_deep($zipDirectory);
                $zipService->extract($zipFile, $zipDirectory);
                $filesToCopy = GeneralUtility::getFilesInDir($zipDirectory);
                if (!is_array($filesToCopy)) {
                    return;
                }
                foreach ($filesToCopy as $fileToCopy) {
                    GeneralUtility::upload_copy_move(
                        $zipDirectory . '/' . $fileToCopy,
                        $targetPath . '/' . $fileToCopy
                    );
                }
                GeneralUtility::rmdir($zipDirectory, true);
                unlink($zipFile);
            }
        } catch (\Exception $e) {
            throw new \RuntimeException('Extracting and copying app icon resources failed: ' . $e->getMessage(), 1649829068, $e);
        }
    }
}
