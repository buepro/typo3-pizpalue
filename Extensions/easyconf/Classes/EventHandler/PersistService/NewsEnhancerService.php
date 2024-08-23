<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\PersistService;

use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class NewsEnhancerService extends AbstractService
{
    public function process(): void
    {
        if (
            (bool)$this->getPropertyValueByFieldName('admin_siteconf_remove_legacy_news_route_enhancers') &&
            $this->hasLegacyRouteEnhancer()
        ) {
            $siteData = $this->siteConfigurationService->getSiteData();
            unset($siteData['routeEnhancers']['NewsPluginDetail'], $siteData['routeEnhancers']['NewsPluginList']);
            if (count($siteData['routeEnhancers']) === 0) {
                unset($siteData['routeEnhancers']);
            }
            $this->siteConfigurationService->writeSiteData($siteData);
        }
        if (!(bool)$this->getPropertyValueByFieldName('admin_siteconf_manage_news_route_enhancer')) {
            return;
        }
        if (ExtensionManagementUtility::isLoaded('news')) {
            $this->setNewsRouteEnhancer();
            return;
        }
        $this->removeNewsRouteEnhancer();
    }

    protected function setNewsRouteEnhancer(): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tt_content');
        $queryBuilder
            ->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        $result = $queryBuilder
            ->select('pid')
            ->from('tt_content')
            ->orWhere(
                $queryBuilder->expr()->eq('list_type', $queryBuilder->createNamedParameter('news_pi1')),
                $queryBuilder->expr()->like('CType', $queryBuilder->createNamedParameter('news_%'))
            )
            ->executeQuery();
        if (
            is_array($records = $result->fetchAllAssociative()) &&
            count($pids = array_column($records, 'pid')) > 0 &&
            (bool)($pids = array_unique($pids)) &&
            sort($pids) &&
            $this->getLimitToPages() !== $pids
        ) {
            $newsRouteEnhancerTemplate = (new YamlFileLoader())->load(
                'EXT:pizpalue/Extensions/news/Configuration/Yaml/Site.yaml'
            );
            $siteData = $this->siteConfigurationService->getSiteData();
            $siteData['routeEnhancers']['PizpalueNews'] = $newsRouteEnhancerTemplate['routeEnhancers']['PizpalueNews'];
            $siteData['routeEnhancers']['PizpalueNews']['limitToPages'] = $pids;
            $this->siteConfigurationService->writeSiteData($siteData);
        }
    }

    protected function removeNewsRouteEnhancer(): void
    {
        $siteData = $this->siteConfigurationService->getSiteData();
        unset($siteData['routeEnhancers']['PizpalueNews']);
        if (isset($siteData['routeEnhancers']) && count($siteData['routeEnhancers']) === 0) {
            unset($siteData['routeEnhancers']);
        }
        $this->siteConfigurationService->writeSiteData($siteData);
    }

    protected function hasLegacyRouteEnhancer(): bool
    {
        $siteData = $this->siteConfigurationService->getSiteData();
        return isset($siteData['routeEnhancers']['NewsPluginDetail']) || isset($siteData['routeEnhancers']['NewsPluginList']);
    }

    protected function getLimitToPages(): array
    {
        return (array)($this->siteConfigurationService->getSiteData()['routeEnhancers']['PizpalueNews']['limitToPages'] ?? []);
    }
}
