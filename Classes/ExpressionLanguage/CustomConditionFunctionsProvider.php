<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class CustomConditionFunctionsProvider implements ExpressionFunctionProviderInterface
{

    /**
     * @inheritDoc
     */
    public function getFunctions(): array
    {
        return [
            $this->getExtensionConfiguration(),
            $this->getExtensionLoaded(),
        ];
    }

    protected function getExtensionConfiguration(): ExpressionFunction
    {
        return new ExpressionFunction(
            'ppExtensionConfiguration',
            static fn () => null,
            static function ($arguments, string $extensionKey, string $constantKey) {
                $extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
                    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
                );
                $selectedExtensionConfiguration = $extensionConfiguration->get($extensionKey);
                if (!is_array($selectedExtensionConfiguration)) {
                    return false;
                }
                return $selectedExtensionConfiguration[$constantKey] ?? false;
            }
        );
    }

    private function getExtensionLoaded(): ExpressionFunction
    {
        return new ExpressionFunction(
            'ppExtensionLoaded',
            static fn () => null,
            static function ($arguments, string $extensionKey) {
                return \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded($extensionKey);
            }
        );
    }
}
