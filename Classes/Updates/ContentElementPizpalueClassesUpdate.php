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
    private $movedToFrameBackgroundClass = [
        'pp-bg-primary' => 'primary',
        'pp-bg-secondary' => 'secondary',
        'pp-bg-complementary' => 'complementary',
        'pp-bg-light' => 'light',
        'bg-primary' => 'primary',
        'bg-secondary' => 'secondary',
        'bg-complementary' => 'complementary',
        'bg-tertiary' => 'tertiary',
        'bg-quaternary' => 'quaternary',
        'bg-light' => 'light',
        'bg-dark' => 'dark',
    ];

    /**
     * @var string[]
     */
    private $movedToInnerClasses = [
        'pp-card-primary' => 'pp-panel pp-panel-primary',
        'pp-card-secondary' => 'pp-panel pp-panel-secondary',
        'pp-card-complementary' => 'pp-panel pp-panel-complementary',
        'pp-card-light' => 'pp-panel pp-panel-light',
        'pp-card-dark' => 'pp-panel pp-panel-dark',
        'pp-inner-margin' => 'pp-margin',
        'pp-inner-padding' => 'pp-padding',
        'pp-inner-bgwhite70' => 'bg-white opacity-75',
        'pp-inner-bggrey70' => 'pp-bg-gray-600 opacity-75',
        'pp-inner-bgblack70' => 'bg-black opacity-75',
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
        return '[Pizpalue] Migrate the content element "Additional classes" field';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'This wizard step checks the content element field "Additional classes"  for classes to be moved ' .
            'to the background color or the inner classes field.';
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

    private function getMovedToFrameBackgroundClassesConstraints(QueryBuilder $queryBuilder): \TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression
    {
        $constraints = [];
        foreach ($this->movedToFrameBackgroundClass as $oldClass => $newClass) {
            $constraints[] = $queryBuilder->expr()->orX(
                $queryBuilder->expr()->like('tx_pizpalue_classes', $queryBuilder->createNamedParameter($oldClass . '%', \PDO::PARAM_STR)),
                $queryBuilder->expr()->like('tx_pizpalue_classes', $queryBuilder->createNamedParameter('% ' . $oldClass . '%', \PDO::PARAM_STR))
            );
        }
        return $queryBuilder->expr()->orX(...$constraints);
    }

    private function getMovedToInnerClassesConstraints(QueryBuilder $queryBuilder): \TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression
    {
        $constraints = [];
        foreach ($this->movedToInnerClasses as $oldClass => $newClass) {
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
            ->where($queryBuilder->expr()->orX(
                $this->getMovedToFrameBackgroundClassesConstraints($queryBuilder),
                $this->getMovedToInnerClassesConstraints($queryBuilder)
            ))
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
        $queryResult = $queryBuilder->select('uid', 'background_color_class', 'tx_pizpalue_classes', 'tx_pizpalue_inner_classes')
            ->from('tt_content')
            ->where($queryBuilder->expr()->orX(
                $this->getMovedToFrameBackgroundClassesConstraints($queryBuilder),
                $this->getMovedToInnerClassesConstraints($queryBuilder)
            ))
            ->execute();
        if (!($queryResult instanceof Result)) {
            return false;
        }
        while (
            is_array($record = $queryResult->fetchAssociative()) &&
            is_string($record['background_color_class']) &&
            is_string($record['tx_pizpalue_classes']) &&
            is_string($record['tx_pizpalue_inner_classes'])
        ) {
            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->update('tt_content')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                    )
                )
                ->set('background_color_class', $this->getFrameBackgroundClass($record['tx_pizpalue_classes']))
                ->set('tx_pizpalue_classes', $this->getPizpalueClasses($record['tx_pizpalue_classes']))
                ->set('tx_pizpalue_inner_classes', $this->getPizpalueInnerClasses(
                    $record['tx_pizpalue_classes'],
                    $record['tx_pizpalue_inner_classes']
                ));
            $queryBuilder->execute();
        }
        return true;
    }

    private function getFrameBackgroundClass(string $classes): string
    {
        $classes = GeneralUtility::trimExplode(' ', $classes, true);
        foreach ($classes as $class) {
            if (isset($this->movedToFrameBackgroundClass[$class])) {
                return $this->movedToFrameBackgroundClass[$class];
            }
        }
        return 'none';
    }

    private function getPizpalueClasses(string $classes): string
    {
        $actualClasses = GeneralUtility::trimExplode(' ', $classes, true);
        $movedClasses = array_merge($this->movedToFrameBackgroundClass, $this->movedToInnerClasses);
        $newClasses = array_filter($actualClasses, function (string $actualClass) use ($movedClasses): bool {
            return !isset($movedClasses[$actualClass]);
        });
        return implode(' ', $newClasses);
    }

    private function getPizpalueInnerClasses(string $classes, string $innerClasses): string
    {
        $classes = GeneralUtility::trimExplode(' ', $classes, true);
        $actualClasses = GeneralUtility::trimExplode(' ', $innerClasses, true);
        foreach ($classes as $class) {
            if (isset($this->movedToInnerClasses[$class])) {
                $actualClasses[] = $this->movedToInnerClasses[$class];
            }
        }
        return implode(' ', array_unique($actualClasses));
    }
}
