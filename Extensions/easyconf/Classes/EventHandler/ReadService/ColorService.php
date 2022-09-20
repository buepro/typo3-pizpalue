<?php

declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Easyconf\EventHandler\ReadService;

class ColorService extends AbstractService
{
    public function process(): array
    {
        $this->setNavbarOpacity();
        return $this->formFields;
    }

    protected function setNavbarOpacity(): self
    {
        $opacity = $this->typoScriptConstantMapper->getProperty('plugin.bootstrap_package.settings.scss.pp-navbar-bg-opacity');
        foreach (['navbar-light-bg-opacity', 'navbar-dark-bg-opacity'] as $propertyName) {
            $fieldName = 'color_' . $propertyName;
            $this->formFields[$fieldName] = $opacity;
        }

        return $this;
    }
}
