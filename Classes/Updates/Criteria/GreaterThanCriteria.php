<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Updates\Criteria;

class GreaterThanCriteria extends AbstractCriteria implements CriteriaInterface
{
    /**
     * @var int
     */
    protected $value = 0;

    public function setValue(int $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->queryBuilder->expr()->gt(
            $this->getField(),
            $this->getValue()
        );
    }
}
