<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Sysext\Core\Resource;

use TYPO3\CMS\Core\Resource\FileReference as CoreFileReference;

class FileReference extends CoreFileReference
{
    protected ?CoreFileReference $poster;

    public static function getFromCoreFileReference(CoreFileReference $coreFileReference): self
    {
        $instance = new self($coreFileReference->propertiesOfFileReference);
        $instance->originalFile = $coreFileReference->originalFile;
        return $instance;
    }

    public function hasPosterReference(): bool
    {
        return (bool)$this->getProperty('tx_pizpalue_poster');
    }

    public function setPoster(CoreFileReference $poster): void
    {
        $this->poster = $poster;
    }

    public function getPoster(): ?CoreFileReference
    {
        return $this->poster;
    }

    public function getPosterWidth(): int
    {
        if ($this->poster === null) {
            return 0;
        }
        return (int)$this->poster->getProperty('tx_pizpalue_poster_width');
    }
}
