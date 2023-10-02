<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Updates;

use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Install\Updates\ChattyInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

class ContentElementHeadingClassesUpdate extends AbstractUpdate implements UpgradeWizardInterface, ChattyInterface
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var string
     */
    protected $title = 'EXT:pizpalue: Migrate the content element heading classes';

    /**
     * @var string
     */
    protected $description =  'The bootstrap package now provides as well fields to alter the headings classes '
        . 'making the pizpalue fields obsolete. This wizard moves the heading settings to the bootstrap package '
        . 'domain. ATTENTION: Each time you run this wizard the field values in the bootstrap package domain are '
        . 'overridden. Use this wizard with care, usually just once.';
    protected function getCriteria(QueryBuilder $queryBuilder): array
    {
        return [$queryBuilder->expr()->or(
            $queryBuilder->expr()->or(
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq('tx_pizpalue_header_class', $queryBuilder->createNamedParameter('none')),
                    $queryBuilder->expr()->neq('header_class', $queryBuilder->createNamedParameter(''))
                ),
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->neq('tx_pizpalue_header_class', $queryBuilder->createNamedParameter('none')),
                    $queryBuilder->expr()->neq(
                        'header_class',
                        $queryBuilder->quoteIdentifier('tx_pizpalue_header_class')
                    )
                )
            ),
            $queryBuilder->expr()->or(
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq('tx_pizpalue_subheader_class', $queryBuilder->createNamedParameter('none')),
                    $queryBuilder->expr()->neq('subheader_class', $queryBuilder->createNamedParameter(''))
                ),
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->neq('tx_pizpalue_subheader_class', $queryBuilder->createNamedParameter('none')),
                    $queryBuilder->expr()->neq(
                        'subheader_class',
                        $queryBuilder->quoteIdentifier('tx_pizpalue_subheader_class')
                    )
                )
            )
        )];
    }

    public function updateNecessary(): bool
    {
        if (
            $this->tableHasColumn('tx_pizpalue_header_class') ||
            $this->tableHasColumn('tx_pizpalue_subheader_class')
        ) {
            return parent::updateNecessary();
        }
        return false;
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
                    'header_class' => $record['tx_pizpalue_header_class'] === 'none' ? '' : $record['tx_pizpalue_header_class'],
                    'subheader_class' => $record['tx_pizpalue_subheader_class'] === 'none' ? '' : $record['tx_pizpalue_subheader_class'],
                ]
            );
        }
        $this->output->write('The heading classes fields have been updated. Please update the database '
            . 'structure with the maintenance module after processing all wizards. By updating the database '
            . 'structure the obsolete heading classes fields from pizpalue will be removed preventing the '
            . 'current heading classes fields to be overwritten when running this wizard again.');
        return true;
    }

    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }
}
