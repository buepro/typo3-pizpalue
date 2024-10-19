<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Updates;

use Buepro\Pizpalue\Updates\Criteria\CriteriaInterface;
use Buepro\Pizpalue\Updates\Criteria\EqualStringCriteria;
use Buepro\Pizpalue\Updates\Criteria\GreaterThanCriteria;
use Buepro\Pizpalue\Updates\Criteria\InCriteria;
use Buepro\Pizpalue\Updates\Criteria\LikeCriteria;
use Buepro\Pizpalue\Updates\Criteria\NotEqualStringCriteria;
use Buepro\Pizpalue\Updates\Criteria\NotLikeCriteria;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;

abstract class AbstractUpdate
{
    const CONDITION_AND = 'AND';
    const CONDITION_OR = 'OR';

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var string
     */
    protected $table = 'tt_content';

    /**
     * @var Connection|null
     */
    protected $connection = null;

    public function getIdentifier(): string
    {
        return get_class($this);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
        ];
    }

    protected function getConnection(): Connection
    {
        if ($this->connection === null) {
            $this->connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($this->table);
        }

        return $this->connection;
    }

    protected function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->getConnection()->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();

        return $queryBuilder;
    }

    protected function tableHasColumn(string $column): bool
    {
        $schemaManager = $this->getConnection()->createSchemaManager();
        $tableColumns = $schemaManager->listTableColumns($this->table);

        if (array_key_exists($column, $tableColumns)) {
            return true;
        }

        return false;
    }

    protected function createGreaterThanCriteria(QueryBuilder $queryBuilder, string $field, int $value): GreaterThanCriteria
    {
        return (new GreaterThanCriteria($queryBuilder, $field))
            ->setValue($value);
    }

    protected function createEqualStringCriteria(QueryBuilder $queryBuilder, string $field, string $value): EqualStringCriteria
    {
        return (new EqualStringCriteria($queryBuilder, $field))
            ->setValue($value);
    }

    protected function createNotEqualStringCriteria(QueryBuilder $queryBuilder, string $field, string $value): NotEqualStringCriteria
    {
        return (new NotEqualStringCriteria($queryBuilder, $field))
            ->setValue($value);
    }

    protected function createInCriteria(QueryBuilder $queryBuilder, string $field, array $values): InCriteria
    {
        return (new InCriteria($queryBuilder, $field))
            ->setValues($values);
    }

    protected function createLikeCriteria(QueryBuilder $queryBuilder, string $field, string $value): LikeCriteria
    {
        return (new LikeCriteria($queryBuilder, $field))
            ->setValue($value);
    }

    protected function createNotLikeCriteria(QueryBuilder $queryBuilder, string $field, string $value): NotLikeCriteria
    {
        return (new NotLikeCriteria($queryBuilder, $field))
            ->setValue($value);
    }

    abstract protected function getCriteria(QueryBuilder $queryBuilder): array;

    protected function getRecordsByCriteria(QueryBuilder $queryBuilder, array $criteria, string $condition = self::CONDITION_AND): array
    {
        $queryBuilder->select('*');
        $queryBuilder->from($this->table);
        if ($condition === self::CONDITION_AND) {
            $queryBuilder->where(...array_map(static fn (CriteriaInterface $criterion): string => (string)$criterion, $criteria));
        } else {
            $queryBuilder->orWhere(...array_map(static fn (CriteriaInterface $criterion): string => (string)$criterion, $criteria));
        }

        $result = $queryBuilder->executeQuery();

        return $result->fetchAllAssociative();
    }

    public function updateNecessary(): bool
    {
        $queryBuilder = $this->createQueryBuilder();
        $records = $this->getRecordsByCriteria($queryBuilder, $this->getCriteria($queryBuilder));

        return (bool) count($records);
    }

    protected function updateRecord(int $uid, array $values): void
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->update($this->table)
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)));

        foreach ($values as $field => $value) {
            $queryBuilder->set($field, $value);
        }

        $queryBuilder->executeStatement();
    }
}
