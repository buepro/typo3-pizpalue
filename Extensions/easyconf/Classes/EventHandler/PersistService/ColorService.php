<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\PersistService;

class ColorService extends AbstractService
{
    public function process(): void
    {
        $this->handleNavbarOpacity();
    }

    protected function handleNavbarOpacity(): self
    {
        $scssPath = 'plugin.bootstrap_package.settings.scss.';
        $menuStyle = $this->getPropertyValueByFieldName('menu_main_style');
        $opacity = '';
        if (strpos($menuStyle, 'default') === 0) {
            $opacity = trim((string)$this->getPropertyValueByFieldName('color_navbar-light-bg-opacity'));
        }
        if (strpos($menuStyle, 'inverse') === 0) {
            $opacity = trim((string)$this->getPropertyValueByFieldName('color_navbar-dark-bg-opacity'));
        }
        if ($opacity === '') {
            // The menu style has changed -> we get the value from ts
            $opacity = $this->typoScriptMapper->getProperty($scssPath . 'pp-navbar-bg-opacity');
        }
        if ($opacity !== '') {
            $this->typoScriptMapper->bufferProperty($scssPath . 'pp-navbar-bg-opacity', $opacity);
        }
        // Remove not used ts properties
        if ($opacity === '??') {
            $this->typoScriptMapper->removePropertyFromBuffer($scssPath . 'pp-navbar-bg-opacity');
        }
        $this->typoScriptMapper->removePropertyFromBuffer($scssPath . 'navbar-light-bg-opacity');
        $this->typoScriptMapper->removePropertyFromBuffer($scssPath . 'navbar-dark-bg-opacity');

        return $this;
    }
}
