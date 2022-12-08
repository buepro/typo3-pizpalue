<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Updates\Criteria;

class LikeCriteria extends AbstractCriteria implements CriteriaInterface
{
    /**
     * @var string
     */
    protected $value = '';

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->queryBuilder->expr()->like(
            $this->getField(),
            $this->queryBuilder->expr()->literal($this->getValue())
        );
    }
}
