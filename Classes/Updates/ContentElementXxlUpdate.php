<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Updates;

use TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

class ContentElementXxlUpdate extends AbstractUpdate implements UpgradeWizardInterface, RepeatableInterface
{
    /**
     * @var string
     */
    protected $title = 'EXT:pizpalue: Migrate the "Scaling" and "Aspect ratio" fields to Bootstrap 5';

    /**
     * @var string
     */
    protected $description =  'Bootstrap 5 introduced the screen breakpoint "xxl". If the fields "Scaling" or "Aspect '
        . 'ratio" from content elements don\'t contain a definition for the new breakpoint default values will be '
        . 'used for the image calculation. In most cases the value defined for the xl-breakpoint is more appropriate. '
        . 'This wizard step will add an "xxl" entry with the value from the defined xl-breakpoint to the fields. '
        . 'In case no xl-breakpoint entry exists nothing will be none.';

    protected function getScalingCriteria(QueryBuilder $queryBuilder): CompositeExpression
    {
        return $queryBuilder->expr()->and(
            (string) $this->createLikeCriteria($queryBuilder, 'tx_pizpalue_image_scaling', '%xl%'),
            (string) $this->createNotLikeCriteria($queryBuilder, 'tx_pizpalue_image_scaling', '%xxl%')
        );
    }

    protected function getAspectRatioCriteria(QueryBuilder $queryBuilder): CompositeExpression
    {
        return $queryBuilder->expr()->and(
            (string) $this->createLikeCriteria($queryBuilder, 'tx_pizpalue_image_aspect_ratio', '%xl%'),
            (string) $this->createNotLikeCriteria($queryBuilder, 'tx_pizpalue_image_aspect_ratio', '%xxl%')
        );
    }

    protected function getCriteria(QueryBuilder $queryBuilder): array
    {
        return [$queryBuilder->expr()->or(
            $this->getScalingCriteria($queryBuilder),
            $this->getAspectRatioCriteria($queryBuilder)
        )];
    }

    /**
     * @inheritDoc
     */
    public function executeUpdate(): bool
    {
        $this->updateField('tx_pizpalue_image_scaling', [$this, 'getScalingCriteria']);
        $this->updateField('tx_pizpalue_image_aspect_ratio', [$this, 'getAspectRatioCriteria']);
        return true;
    }

    private function updateField(string $fieldName, callable $getCriteriaMethod): void
    {
        $queryBuilder = $this->createQueryBuilder();
        $records = $this->getRecordsByCriteria($queryBuilder, [$getCriteriaMethod($queryBuilder)]);

        foreach ($records as $record) {
            $this->updateRecord(
                (int) $record['uid'],
                [$fieldName => $this->addXxlBreakpoint($record[$fieldName])]
            );
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
