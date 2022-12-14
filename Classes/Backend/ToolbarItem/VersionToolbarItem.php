<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Backend\ToolbarItem;

use TYPO3\CMS\Backend\Backend\Event\SystemInformationToolbarCollectorEvent;
use TYPO3\CMS\Backend\Backend\ToolbarItems\SystemInformationToolbarItem;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * VersionToolbarItem
 * Based on `bootstrap_package`
 */
class VersionToolbarItem
{
    public function __invoke(SystemInformationToolbarCollectorEvent $event): void
    {
        $this->addVersionInformation($event->getToolbarItem());
    }

    /**
     * Called by the system information toolbar signal/slot dispatch.
     */
    public function addVersionInformation(SystemInformationToolbarItem $systemInformation): void
    {
        $value = null;
        $extensionDirectory = ExtensionManagementUtility::extPath('pizpalue');

        // Try to get current version from git
        if (file_exists($extensionDirectory . '.git')) {
            $returnCode = 0;
            CommandUtility::exec('git --version', $_, $returnCode);
            if ((int)$returnCode === 0 && ($currentDir = getcwd()) !== false) {
                chdir($extensionDirectory);
                $tag = trim(CommandUtility::exec('git tag -l --points-at HEAD'));
                if ((bool)$tag) {
                    $value = $tag;
                } else {
                    $branch = trim(CommandUtility::exec('git rev-parse --abbrev-ref HEAD'));
                    $revision = trim(CommandUtility::exec('git rev-parse --short HEAD'));
                    $value = $branch . ', ' . $revision;
                }
                chdir($currentDir);
            }
        }

        // Fallback to version from extension manager
        if ($value === null) {
            $value = ExtensionManagementUtility::getExtensionVersion('pizpalue');
        }

        // Remove leading v
        if ($value[0] === 'v' && str_contains($value, '.')) {
            $value = substr($value, 1);
        }

        // Set system information entry
        $systemInformation->addSystemInformation(
            'Pizpalue Template',
            htmlspecialchars($value, ENT_QUOTES | ENT_HTML5),
            'systeminformation-pizpalue'
        );
    }
}
