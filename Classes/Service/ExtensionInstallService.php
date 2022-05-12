<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Service;

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Package\Event\AfterPackageActivationEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extensionmanager\Event\AfterExtensionFilesHaveBeenImportedEvent;
use TYPO3\CMS\Extensionmanager\Exception\ExtensionManagerException;

class ExtensionInstallService
{
    /**
     * @param AfterExtensionFilesHaveBeenImportedEvent | AfterPackageActivationEvent $event
     * @return void
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws ExtensionManagerException
     */
    public function __invoke($event): void
    {
        $this->afterExtensionInstall($event->getPackageKey());
    }

    /**
     * Copies the translation files for the bootstrap_package to the typo3conf/l10n directory.
     *
     * @return bool false if translation files couldn't be copied
     */
    private function copyBootstrapPackageTranslations()
    {
        $source = Environment::getPublicPath() . '/typo3conf/ext/pizpalue/Resources/Private/'
            . 'FolderStructureTemplateFiles/l10n/fi/bootstrap_package';
        $destination = Environment::getPublicPath() . '/typo3conf/l10n/fi/bootstrap_package';
        if (!file_exists($destination)) {
            GeneralUtility::copyDirectory($source, $destination);
            if (!file_exists($destination)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Copies language translations for bootstrap_package
     *
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws ExtensionManagerException
     */
    public function afterExtensionInstall(string $extensionKey): void
    {
        if ($extensionKey !== 'pizpalue') {
            return;
        }
        $this->copyBootstrapPackageTranslations();
    }
}
