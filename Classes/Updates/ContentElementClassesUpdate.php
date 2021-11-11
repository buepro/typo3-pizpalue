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

class ContentElementClassesUpdate implements UpgradeWizardInterface, RepeatableInterface
{
    /**
     * @var string[]
     */
    private $replacementClasses = [
        'no-gutters' => 'g-0',
        'left-' => 'start-',
        'right-' => 'end-',
        'float-left' => 'float-start',
        'float-right' => 'float-end',
        'border-left' => 'border-start',
        'border-right' => 'border-end',
        'rounded-left' => 'rounded-start',
        'rounded-right' => 'rounded-end',
        'ml-' => 'ms-',
        'mr-' => 'me-',
        'pl-' => 'ps-',
        'pr-' => 'pe-',
        'text-left' => 'text-start',
        'text-right' => 'text-end',
        'text-monospace' => 'font-monospace',
        'font-weight' => 'fw-',
        'font-style' => 'fst-',
        'rounded-sm' => 'rounded-1',
        'rounded-lg' => 'rounded-3',
        'ratio-1by1' => 'ratio-1x1',
        'ratio-4by3' => 'ratio-4x3',
        'ratio-16by9' => 'ratio-16x9',
        'ratio-21by9' => 'ratio-21x9',
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
        return '[Pizpalue] Migrate the "Additional classes" field to Bootstrap 5';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Bootstrap 5 replaced some css classes. This wizard step checks the filed "Additional classes" for '
            . 'replacement classes and adds the new classes as needed. The resulting classes should be compatible '
            . 'with Bootstrap 4 and Bootstrap 5.';
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
            $constraints[] = $queryBuilder->expr()->andX(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('tx_pizpalue_classes', $queryBuilder->createNamedParameter($oldClass . '%', \PDO::PARAM_STR)),
                    $queryBuilder->expr()->like('tx_pizpalue_classes', $queryBuilder->createNamedParameter('% ' . $oldClass . '%', \PDO::PARAM_STR))
                ),
                $queryBuilder->expr()->notLike('tx_pizpalue_classes', $queryBuilder->createNamedParameter($newClass . '%', \PDO::PARAM_STR)),
                $queryBuilder->expr()->notLike('tx_pizpalue_classes', $queryBuilder->createNamedParameter('% ' . $newClass . '%', \PDO::PARAM_STR))
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
        while (is_array($record = $queryResult->fetchAssociative())) {
            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->update('tt_content')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                    )
                )
                ->set('tx_pizpalue_classes', $this->addNewClasses($record['tx_pizpalue_classes']));
            $queryBuilder->execute();
        }
        return true;
    }

    private function addNewClasses(string $classes): string
    {
        $actualClasses = GeneralUtility::trimExplode(' ', $classes, true);
        $newClasses = [];
        foreach ($actualClasses as $actualClass) {
            foreach ($this->replacementClasses as $oldClass => $newClass) {
                if (strpos($actualClass, $oldClass) === 0) {
                    $tail = GeneralUtility::trimExplode($oldClass, $actualClass, true, 1);
                    $newClass = implode('', array_merge([$newClass], $tail));
                    if (strpos($classes, $newClass) === false) {
                        $newClasses[] = $newClass;
                    }
                }
            }
        }
        return implode(' ', array_merge($actualClasses, $newClasses));
    }
}
