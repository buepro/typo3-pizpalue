<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Updates;

use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

class ContentElementBootstrapClassesUpdate extends AbstractUpdate implements UpgradeWizardInterface, RepeatableInterface
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
     * @var string
     */
    protected $title = 'EXT:pizpalue: Migrate the "Additional classes" field to Bootstrap 5';

    /**
     * @var string
     */
    protected $description = 'Bootstrap 5 replaced some css classes. This wizard step checks the filed "Additional '
        . 'classes" for replacement classes and adds the new classes as needed. The resulting classes should be '
        . 'compatible with Bootstrap 4 and Bootstrap 5.';

    /**
     * @var string
     */
    protected $field = 'tx_pizpalue_classes';

    protected function getCriteria(QueryBuilder $queryBuilder): array
    {
        $criteria = [];
        foreach ($this->replacementClasses as $oldValue => $newValue) {
            $criteria[] = $queryBuilder->expr()->and(
                (string) $this->createLikeCriteria($queryBuilder, $this->field, "%$oldValue%"),
                (string) $this->createNotLikeCriteria($queryBuilder, $this->field, "%$newValue%")
            );
        }
        return [$queryBuilder->expr()->or(...$criteria)];
    }

    /**
     * @inheritDoc
     */
    public function executeUpdate(): bool
    {
        $queryBuilder = $this->createQueryBuilder();
        $records = $this->getRecordsByCriteria($queryBuilder, $this->getCriteria($queryBuilder));

        foreach ($records as $record) {
            $this->updateRecord(
                (int) $record['uid'],
                [$this->field => $this->addNewClasses($record[$this->field])]
            );
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
