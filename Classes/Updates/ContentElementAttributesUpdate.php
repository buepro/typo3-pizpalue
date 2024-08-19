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
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('ppContentElementAttributesUpdate')]
class ContentElementAttributesUpdate extends AbstractUpdate implements UpgradeWizardInterface, RepeatableInterface
{
    /**
     * @var string[]
     */
    private $renameList = [
        'data-josh-delay' => 'data-josh-anim-delay',
    ];

    /**
     * @var string
     */
    protected $title = 'EXT:pizpalue: Migrate the "Additional attributes" field';

    /**
     * @var string
     */
    protected $description = 'Rename the attribute "data-josh-delay" to "data-josh-anim-delay".';

    /**
     * @var string
     */
    protected $table = 'tt_content';

    /**
     * @var string
     */
    protected $field = 'tx_pizpalue_attributes';

    protected function getCriteria(QueryBuilder $queryBuilder): array
    {
        $criteria = [];
        foreach ($this->renameList as $oldValue => $newValue) {
            $criteria[] = $this->createLikeCriteria($queryBuilder, $this->field, "%$oldValue%");
        }
        return $criteria;
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
                [$this->field => $this->getRenamedAttributes($record[$this->field])]
            );
        }

        return true;
    }

    private function getRenamedAttributes(string $attributes): string
    {
        return str_replace(array_keys($this->renameList), $this->renameList, $attributes);
    }
}
