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

class ContentElementPizpalueClassesUpdate extends AbstractUpdate implements UpgradeWizardInterface, RepeatableInterface
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
     * @var string
     */
    protected $title = 'EXT:pizpalue: Migrate the content element "Additional classes" field';

    /**
     * @var string
     */
    protected $description =  'This wizard step checks the content element field "Additional classes"  for classes to be moved ' .
            'to the background color or the inner classes field.';

    /**
     * @var string
     */
    protected $field = 'tx_pizpalue_classes';

    private function getMovedToFrameBackgroundClassesConstraints(QueryBuilder $queryBuilder): \TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression
    {
        $constraints = [];
        foreach ($this->movedToFrameBackgroundClass as $oldClass => $newClass) {
            $constraints[] = (string) $this->createLikeCriteria($queryBuilder, $this->field, "%$oldClass%");
        }
        return $queryBuilder->expr()->or(...$constraints);
    }

    private function getMovedToInnerClassesConstraints(QueryBuilder $queryBuilder): \TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression
    {
        $constraints = [];
        foreach ($this->movedToInnerClasses as $oldClass => $newClass) {
            $constraints[] = (string) $this->createLikeCriteria($queryBuilder, $this->field, "%$oldClass%");
        }
        return $queryBuilder->expr()->or(...$constraints);
    }

    protected function getCriteria(QueryBuilder $queryBuilder): array
    {
        return [$queryBuilder->expr()->or(
            $this->getMovedToFrameBackgroundClassesConstraints($queryBuilder),
            $this->getMovedToInnerClassesConstraints($queryBuilder)
        )];
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
                    'background_color_class' => $this->getFrameBackgroundClass($record[$this->field]),
                    $this->field => $this->getPizpalueClasses($record[$this->field]),
                    'tx_pizpalue_inner_classes' => $this->getPizpalueInnerClasses(
                        $record[$this->field],
                        $record['tx_pizpalue_inner_classes']
                    ),
                ]
            );
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
