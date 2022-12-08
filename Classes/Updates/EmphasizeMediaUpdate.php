<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Updates;

use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

class EmphasizeMediaUpdate extends AbstractUpdate implements UpgradeWizardInterface, RepeatableInterface
{
    /**
     * @var string
     */
    protected $title = 'EXT:pizpalue: Migrate layout "Emphasize media" to content element';

    /**
     * @var string
     */
    protected $description = 'The layout option "Emphasize media" has been removed in favor of an own content element. '
            . 'This wizard converts content elements using the "Emphasize media" layout to the related content type. '
            . 'Please note that this conversion leads to the frame class "frame-layout-pp-emphasize-media" being dropped '
            . 'hence the site package should be reviewed for possible usages.';

    /**
     * @var string
     */
    protected $field = 'layout';

    protected function getCriteria(QueryBuilder $queryBuilder): array
    {
        return [
            $this->createEqualStringCriteria($queryBuilder, $this->field, 'pp-emphasize-media'),
            $this->createNotEqualStringCriteria($queryBuilder, $this->field, '0'),
        ];
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
                [
                    'CType' => 'pp_emphasize_media',
                    'layout' => '0',
                ]
            );
        }

        return true;
    }
}
