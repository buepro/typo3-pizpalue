<?php
defined('TYPO3_MODE') or die('Access denied.');


/***************
 * Register icons
 */

call_user_func(
    function ($extConfString) {

        // register icons
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

        $iconRegistry->registerIcon(
            'tx-pizpalue-ppContainerFixed',
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:pizpalue/Resources/Public/Icons/ppContainerFixed.png']
        );

        $iconRegistry->registerIcon(
            'tx-pizpalue-ppContainer',
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:pizpalue/Resources/Public/Icons/ppContainer.png']
        );

    },$_EXTCONF
);
