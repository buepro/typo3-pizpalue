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
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

class ContentElementXxlUpdate implements UpgradeWizardInterface, RepeatableInterface
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
        return '[Pizpalue] Migrate the "Scaling" and "Aspect ratio" fields to Bootstrap 5';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Bootstrap 5 introduced the screen breakpoint "xxl". If the fields "Scaling" or "Aspect ratio" from '
            . 'content elements don\'t contain a definition for the new breakpoint default values will be used '
            . 'for the image calculation. In most cases the value defined for the xl-breakpoint is more appropriate. '
            . 'This wizard step will add an "xxl" entry with the value from the defined xl-breakpoint to the fields. '
            . 'In case no xl-breakpoint entry exists nothing will be none.';
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

    /**
     * @inheritDoc
     */
    public function updateNecessary(): bool
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        $result = $queryBuilder->count('uid')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->like(
                            'tx_pizpalue_image_scaling',
                            $queryBuilder->createNamedParameter('%xl%', \PDO::PARAM_STR)
                        ),
                        $queryBuilder->expr()->notLike(
                            'tx_pizpalue_image_scaling',
                            $queryBuilder->createNamedParameter('%xxl%', \PDO::PARAM_STR)
                        )
                    ),
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->like(
                            'tx_pizpalue_image_aspect_ratio',
                            $queryBuilder->createNamedParameter('%xl%', \PDO::PARAM_STR)
                        ),
                        $queryBuilder->expr()->notLike(
                            'tx_pizpalue_image_aspect_ratio',
                            $queryBuilder->createNamedParameter('%xxl%', \PDO::PARAM_STR)
                        )
                    )
                )
            )
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
        $this->updateField('tx_pizpalue_image_scaling');
        $this->updateField('tx_pizpalue_image_aspect_ratio');
        return true;
    }

    private function updateField(string $fieldName): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        $queryResult = $queryBuilder->select('uid', $fieldName)
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->like($fieldName, $queryBuilder->createNamedParameter('%xl%', \PDO::PARAM_STR)),
                $queryBuilder->expr()->notLike($fieldName, $queryBuilder->createNamedParameter('%xxl%', \PDO::PARAM_STR))
            )
            ->execute();
        if (!($queryResult instanceof Result)) {
            return;
        }
        while (is_array($record = $queryResult->fetchAssociative()) && is_string($record[$fieldName])) {
            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->update('tt_content')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                    )
                )
                ->set($fieldName, $this->addXxlBreakpoint($record[$fieldName]));
            $queryBuilder->execute();
        }
    }

    private function addXxlBreakpoint(string $breakpoints): string
    {
        $breakpoints = str_replace([',', chr(10), chr(13)], ',', $breakpoints);
        $items = GeneralUtility::trimExplode(',', $breakpoints, true);
        $items = array_reverse($items);
        foreach ($items as $item) {
            $parts = GeneralUtility::trimExplode(':', $item, true);
            if (count($parts) === 2 && $parts[0] === 'xl') {
                $items[] = 'xxl: ' . $parts[1];
            }
        }
        return implode(',' . chr(10), array_reverse($items));
    }
}
