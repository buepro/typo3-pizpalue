<?php
declare(strict_types = 1);

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Service;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Package\Event\AfterPackageActivationEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExtensionInstallService
{
    public function __invoke(AfterPackageActivationEvent $event): void
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
     * @param $extensionKey
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     * @throws \TYPO3\CMS\Extensionmanager\Exception\ExtensionManagerException
     */
    public function afterExtensionInstall($extensionKey)
    {
        if ($extensionKey !== 'pizpalue') {
            return;
        }
        $this->copyBootstrapPackageTranslations();
    }
}
