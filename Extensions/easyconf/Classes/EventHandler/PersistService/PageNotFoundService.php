<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\PersistService;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageNotFoundService extends AbstractService
{
    public function process(): void
    {
        if (!(bool)$this->getPropertyValueByFieldName('admin_siteconf_overwrite_page_not_found')) {
            return;
        }
        $record = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('pages')
            ->select(['uid'], 'pages', ['title' => '404'])
            ->fetchAssociative();
        if (!is_array($record) || ($uid = (int)$record['uid']) < 1) {
            return;
        }
        $errorHandling = $this->siteConfigurationService->getSiteData()['errorHandling'] ?? [];
        $key = (int)array_search(404, array_column($errorHandling, 'errorCode'), false);
        $path = 'errorHandling.' . $key . '.';
        $this->siteConfigurationMapper->bufferProperty($path . 'errorCode', 404);
        $this->siteConfigurationMapper->bufferProperty($path . 'errorHandler', 'Page');
        $this->siteConfigurationMapper->bufferProperty($path . 'errorContentSource', 't3://page?uid=' . $uid);
    }
}
