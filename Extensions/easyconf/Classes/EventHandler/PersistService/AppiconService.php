<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\PersistService;

use Buepro\Pizpalue\Easyconf\Utility\AppIconUtility;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Service\Archive\ZipService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AppiconService extends AbstractService
{
    protected const APP_ICON_TEXT_TEMPLATE =
        '<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=###TIMESTAMP###">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=###TIMESTAMP###">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=###TIMESTAMP###">
<link rel="manifest" href="/site.webmanifest?v=###TIMESTAMP###">
<link rel="mask-icon" href="/safari-pinned-tab.svg?v=###TIMESTAMP###" color="###PRIMARY_COLOR###">
<link rel="shortcut icon" href="/favicon.ico?v=###TIMESTAMP###">
<meta name="msapplication-TileColor" content="#eeeeee">
<meta name="theme-color" content="###PRIMARY_COLOR###">';

    /** @var string $primaryColor */
    protected $primaryColor = '#888888';

    public function process(): void
    {
        $this->primaryColor = $this->getPropertyValueByFieldName('color_primary');
        if (!(bool)preg_match('/#[\dabcdef]{6}/', $this->primaryColor)) {
            $this->primaryColor = '#888888';
        }
        $this->handleAppIconArchive()->handleAppIconText()->handleFaviconFile();
    }

    protected function handleAppIconArchive(): self
    {
        if ((int)$this->configurationRecord['appicon_generator_archive'] > 0) {
            $fileRepository = GeneralUtility::makeInstance(FileRepository::class);
            /** @var FileReference $fileReference */
            [$fileReference] = $fileRepository->findByRelation(
                'tx_easyconf_configuration',
                'appicon_generator_archive',
                $this->configurationRecord['uid']
            );
            $this->unzipAndCopy($fileReference->getOriginalFile()->getForLocalProcessing(), Environment::getPublicPath());
        }
        return $this;
    }

    protected function handleAppIconText(): self
    {
        $appIconTextWithoutLineBreaks = $this->getAppIconTextWithoutLineBreaks();
        if (
            (int)$this->configurationRecord['appicon_generator_archive'] > 0 &&
            ($appIconTextWithoutLineBreaks === '' || $this->isAppIconTextGenerated($appIconTextWithoutLineBreaks))
        ) {
            $appIconTextWithoutLineBreaks = $this->generateAppIconTextWithoutLineBreaks();
        }
        $this->typoScriptMapper->bufferProperty(
            'pizpalue.appIcon.headerData',
            $appIconTextWithoutLineBreaks
        );
        return $this;
    }

    protected function handleFaviconFile(): self
    {
        if ((int)$this->configurationRecord['appicon_generator_archive'] > 0) {
            $this->typoScriptMapper->bufferProperty('page.favicon.file', 'AppIcon');
            return $this;
        }
        $appIconTextWithoutLineBreaks = $this->getAppIconTextWithoutLineBreaks();
        if (
            trim($this->getPropertyValueByFieldName('appicon_file')) === 'AppIcon' &&
            ($appIconTextWithoutLineBreaks === '' || $this->isAppIconTextGenerated($appIconTextWithoutLineBreaks))
        ) {
            // Reset
            $this->typoScriptMapper->bufferProperty(
                'page.favicon.file',
                $this->typoScriptMapper->getInheritedProperty('page.favicon.file')
            );
        }
        return $this;
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

    protected function isAppIconTextGenerated(string $appIconTextWithoutLineBreaks): bool
    {
        $reverseTemplate = preg_replace('/(\?v=)(\d*)/', '$1###TIMESTAMP###', $appIconTextWithoutLineBreaks);
        $reverseTemplate = str_replace($this->primaryColor, '###PRIMARY_COLOR###', $reverseTemplate);
        return AppIconUtility::getHtmlWithoutLineBreaks(self::APP_ICON_TEXT_TEMPLATE) === $reverseTemplate;
    }

    protected function generateAppIconTextWithoutLineBreaks(): string
    {
        return AppIconUtility::getHtmlWithoutLineBreaks(str_replace(
            ['###TIMESTAMP###', '###PRIMARY_COLOR###'],
            [(string)time(), $this->primaryColor],
            self::APP_ICON_TEXT_TEMPLATE
        ));
    }

    protected function getAppIconTextWithoutLineBreaks(): string
    {
        $appIconText = $this->formFields['appicon_generator_text'] ??
            $this->typoScriptMapper->getProperty('pizpalue.appIcon.headerData');
        return AppIconUtility::getHtmlWithoutLineBreaks($appIconText);
    }
}
