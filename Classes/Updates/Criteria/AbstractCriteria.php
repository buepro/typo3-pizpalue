<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Updates\Criteria;

use TYPO3\CMS\Core\Database\Query\QueryBuilder;

abstract class AbstractCriteria
{
    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @var string
     */
    protected $field;

    public function __construct(QueryBuilder $queryBuilder, string $field)
    {
        $this->queryBuilder = $queryBuilder;
        $this->field = $field;
    }

    public function getField(): string
    {
        return $this->field;
    }
}
