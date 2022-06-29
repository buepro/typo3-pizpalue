<?php

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

class EmphasizeMediaUpdate implements UpgradeWizardInterface, RepeatableInterface
{

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
        return '[Pizpalue] Migrate layout "Emphasize media" to content element';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'The layout option "Emphasize media" has been removed in favor of an own content element. '
            . 'As a result the frame class "frame-layout-pp-emphasize-media" isn\'t used anymore. Please review '
            . 'your site package for possible references to this class and correct them accordingly.';
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

    private function getConstraints(QueryBuilder $queryBuilder): array
    {
        return [
            $queryBuilder->expr()->eq(
                'layout',
                $queryBuilder->createNamedParameter('pp-emphasize-media', \PDO::PARAM_STR)
            ),
            $queryBuilder->expr()->neq(
                'layout',
                $queryBuilder->createNamedParameter('0', \PDO::PARAM_STR)
            )
        ];
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
            ->where(...$this->getConstraints($queryBuilder))
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
        $queryResult = $queryBuilder->select('uid', 'CType', 'layout')
            ->from('tt_content')
            ->where(...$this->getConstraints($queryBuilder))
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
                ->set('CType', 'pp_emphasize_media')
                ->set('layout', '0');
            $queryBuilder->execute();
        }
        return true;
    }
}
