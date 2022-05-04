<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\PersistService;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class UrlService extends AbstractService
{
    /** @var string With trailing slash */
    protected $mainUrl;

    public function process(): void
    {
        if (GeneralUtility::isValidUrl($this->formFields['customer_url'])) {
            $this->mainUrl = rtrim($this->formFields['customer_url'], '/') . '/';
            $this
                ->setDomain()
                ->overwriteBase()
                ->overwriteRobots()
                ->overwriteSitemap();
        }
        if (GeneralUtility::isValidUrl($this->formFields['customer_alternative_url'])) {
            $this->overwriteBaseVariants();
        }
    }

    protected function setDomain(): self
    {
        $domain = parse_url($this->formFields['customer_url'], PHP_URL_HOST);
        $this->typoScriptMapper->bufferProperty('pizpalue.customer.domain', $domain);
        return $this;
    }

    protected function overwriteBase(): self
    {
        if (!(bool)$this->getPropertyValueByFieldName('admin_siteconf_overwrite_base')) {
            return $this;
        }
        $this->siteConfigurationMapper->bufferProperty('base', $this->mainUrl);
        // Set language base
        if (($languages = $this->siteConfigurationService->getSiteData()['languages'] ?? null) !== null) {
            foreach ($languages as $key => $language) {
                if (!isset($language['languageId'], $language['iso-639-1'])) {
                    continue;
                }
                if ((int)$language['languageId'] === 0) {
                    $this->siteConfigurationMapper->bufferProperty('languages.' . $key . '.base', '/');
                } else {
                    $languageBase = $this->mainUrl . $language['iso-639-1'] . '/';
                    $this->siteConfigurationMapper->bufferProperty('languages.' . $key . '.base', $languageBase);
                }
            }
        }
        $this->siteConfigurationMapper->persistBuffer();
        return $this;
    }

    protected function overwriteBaseVariants(): self
    {
        $fieldName = 'customer_alternative_url';
        if (!(bool)$this->getPropertyValueByFieldName('admin_siteconf_overwrite_base_variants')) {
            return $this;
        }
        $base = rtrim($this->formFields[$fieldName], '/') . '/';
        $domain = parse_url($this->formFields[$fieldName], PHP_URL_HOST);
        $this->siteConfigurationMapper
            ->bufferProperty('baseVariants.0.base', $base)
            ->bufferProperty('baseVariants.0.condition', sprintf('getenv("HTTP_HOST") == "%s"', $domain))
            ->persistBuffer();
        return $this;
    }

    protected function overwriteRobots(): self
    {
        if (!(bool)$this->getPropertyValueByFieldName('admin_siteconf_overwrite_robots')) {
            return $this;
        }
        $siteData = $this->siteConfigurationService->getSiteData();
        $routes = $siteData['routes'] ?? [];
        $key = array_search('robots.txt', array_column($routes, 'route'), true);
        $key = is_int($key) ? $key : count($routes);
        $path = 'routes.' . $key . '.';
        $content = implode("\r\n", [
            'User-agent: *',
            'Disallow: /typo3/',
            'Disallow: /typo3_src/',
            'Allow: /typo3/sysext/frontend/Resources/Public/*',
            sprintf("\r\nSitemap: %ssitemap.xml\r\n", $this->mainUrl)
        ]);
        $this->siteConfigurationMapper
            ->bufferProperty($path . 'route', 'robots.txt')
            ->bufferProperty($path . 'type', 'staticText')
            ->bufferProperty($path . 'content', $content)
            ->persistBuffer();
        return $this;
    }

    protected function overwriteSitemap(): self
    {
        if (!(bool)$this->getPropertyValueByFieldName('admin_siteconf_overwrite_sitemap')) {
            return $this;
        }
        $siteData = $this->siteConfigurationService->getSiteData();
        $routes = $siteData['routes'] ?? [];
        $key = array_search('sitemap.xml', array_column($routes, 'route'), true);
        $key = is_int($key) ? $key : count($routes);
        $path = 'routes.' . $key . '.';
        $source = sprintf('%s?type=1533906435', $this->mainUrl);
        $this->siteConfigurationMapper
            ->bufferProperty($path . 'route', 'sitemap.xml')
            ->bufferProperty($path . 'type', 'uri')
            ->bufferProperty($path . 'source', $source)
            ->persistBuffer();
        return $this;
    }
}
