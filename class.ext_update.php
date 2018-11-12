<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extensionmanager\Utility\InstallUtility;

/**
 * Class ext_update
 *
 * This class provides methods to update pizpalue-installations through the extension manager. Currently it supports
 * updating the data base structure.
 */
class ext_update
{
    const SEVERITY_NOTICE = -2;
    const SEVERITY_INFO = -1;
    const SEVERITY_OK = 0;
    const SEVERITY_WARNING = 1;
    const SEVERITY_ERROR = 2;

    protected $classes = [
        self::SEVERITY_NOTICE => 'notice',
        self::SEVERITY_INFO => 'info',
        self::SEVERITY_OK => 'success',
        self::SEVERITY_WARNING => 'warning',
        self::SEVERITY_ERROR => 'danger'
    ];

    protected $icons = [
        self::SEVERITY_NOTICE => 'lightbulb-o',
        self::SEVERITY_INFO => 'info',
        self::SEVERITY_OK => 'check',
        self::SEVERITY_WARNING => 'exclamation',
        self::SEVERITY_ERROR => 'times'
    ];

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager Extbase Object Manager
     */
    protected $objectManager;

    /**
     * @var \TYPO3\CMS\Extensionmanager\Utility\InstallUtility Extension Manager Install Tool
     */
    protected $installTool;

    /**
     * @param $severity
     * @return string
     * @see fluid InfoboxViewHelper.php
     */
    private function getDialogIcon($severity)
    {
        return  '<div class="media-left">' .
            '<span class="fa-stack fa-lg callout-icon">' .
            '<i class="fa fa-circle fa-stack-2x"></i>' .
            '<i class="fa fa-' . $this->icons[$severity] . ' fa-stack-1x"></i>' .
            '</span>' .
            '</div>';
    }

    /**
     * @param $title
     * @return string
     * @see fluid InfoboxViewHelper.php
     */
    private function getDialogTitle($title)
    {
        return '<h4 class="callout-title">' . $title . '</h4>';
    }

    /**
     * @param $severity
     * @param $title
     * @param $message
     * @return string
     * @see fluid InfoboxViewHelper.php
     */
    private function getDialog($severity, $title, $message)
    {
        return  '<div class="callout callout-' . $this->classes[$severity] . '">' .
            '<div class="media">' .
            $this->getDialogIcon($severity) .
            '<div class="media-body">' .
            $this->getDialogTitle($title) .
            '<div class="callout-body">' . $message . '</div>' .
            '</div>' .
            '</div>' .
            '</div>';
    }

    public function main()
    {
        // update data base structure
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->installTool = $this->objectManager->get(InstallUtility::class);

        $extensionSitePath = ExtensionManagementUtility::extPath('pizpalue');
        $extTablesSqlFile = $extensionSitePath . 'ext_tables.sql';
        $extTablesSqlContent = '';
        if (file_exists($extTablesSqlFile)) {
            $extTablesSqlContent .= GeneralUtility::getUrl($extTablesSqlFile);
        }
        if ($extTablesSqlContent !== '') {
            // TODO: substitute depreciated function
            $this->installTool->updateDbWithExtTablesSql($extTablesSqlContent);
            return $this->getDialog(self::SEVERITY_OK, 'Database updated', 'The database has been updated with the current table definition.');
        } else {
            return $this->getDialog(self::SEVERITY_ERROR, 'Database update', 'The database update failed due to a missing table definition file');
        }
    }

    public function access()
    {
        return true;
    }
}
