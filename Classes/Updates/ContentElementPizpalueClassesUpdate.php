<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Updates;

use Doctrine\DBAL\ForwardCompatibility\Result;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

class ContentElementPizpalueClassesUpdate implements UpgradeWizardInterface, RepeatableInterface
{
    /**
     * @var string[]
     */
    private $replacementClasses = [
        'pp-bg-primary' => 'bg-primary',
        'pp-bg-secondary' => 'bg-secondary',
        'pp-bg-complementary' => 'bg-complementary',
        'pp-bg-light' => 'bg-light',
        'pp-card-primary' => 'pp-inner-panel pp-inner-panel-primary',
        'pp-card-secondary' => 'pp-inner-panel pp-inner-panel-secondary',
        'pp-card-complementary' => 'pp-inner-panel pp-inner-panel-complementary',
        'pp-card-light' => 'pp-inner-panel pp-inner-panel-light',
        'pp-card-dark' => 'pp-inner-panel pp-inner-panel-dark',
    ];

    /**
     * @inheritDoc
     */
    public function getIdentifier(): string
    {
        return self::class;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return '[Pizpalue] Migrate the "Additional classes" field';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'This wizard step replaces changed classes used in the filed "Additional classes".';
    }

    /**
     * @inheritDoc
     */
    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }

    private function getConstraints(QueryBuilder $queryBuilder): \TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression
    {
        $constraints = [];
        foreach ($this->replacementClasses as $oldClass => $newClass) {
            $constraints[] = $queryBuilder->expr()->orX(
                $queryBuilder->expr()->like('tx_pizpalue_classes', $queryBuilder->createNamedParameter($oldClass . '%', \PDO::PARAM_STR)),
                $queryBuilder->expr()->like('tx_pizpalue_classes', $queryBuilder->createNamedParameter('% ' . $oldClass . '%', \PDO::PARAM_STR))
            );
        }
        return $queryBuilder->expr()->orX(...$constraints);
    }

    /**
     * @inheritDoc
     */
    public function updateNecessary(): bool
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        $result = $queryBuilder->count('uid')
            ->from('tt_content')
            ->where($this->getConstraints($queryBuilder))
            ->execute();
        if ($result instanceof Result) {
            return (bool)$result->fetchOne();
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function executeUpdate(): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        $queryResult = $queryBuilder->select('uid', 'tx_pizpalue_classes')
            ->from('tt_content')
            ->where($this->getConstraints($queryBuilder))
            ->execute();
        if (!($queryResult instanceof Result)) {
            return false;
        }
        while (is_array($record = $queryResult->fetchAssociative()) && is_string($record['tx_pizpalue_classes'])) {
            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->update('tt_content')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                    )
                )
                ->set('tx_pizpalue_classes', $this->replaceClasses($record['tx_pizpalue_classes']));
            $queryBuilder->execute();
        }
        return true;
    }

    private function replaceClasses(string $classes): string
    {
        $actualClasses = GeneralUtility::trimExplode(' ', $classes, true);
        $newClasses = array_map(function (string $actualClass): string {
            return $this->replacementClasses[$actualClass] ?? $actualClass;
        }, $actualClasses);
        return implode(' ', $newClasses);
    }
}
