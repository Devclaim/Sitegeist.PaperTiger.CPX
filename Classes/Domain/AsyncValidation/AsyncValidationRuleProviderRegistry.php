<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\AsyncValidation;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;

#[Flow\Scope('singleton')]
final class AsyncValidationRuleProviderRegistry
{
    /**
     * @var list<AsyncValidationRuleProviderInterface>|null
     */
    private ?array $providers = null;

    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
        private readonly ConfigurationManager $configurationManager,
    ) {
    }

    /**
     * @return list<AsyncValidationRuleProviderInterface>
     */
    public function all(): array
    {
        if (is_array($this->providers)) {
            return $this->providers;
        }

        /** @var array<string> $configured */
        $configured = $this->configurationManager->getConfiguration(
            ConfigurationManager::CONFIGURATION_TYPE_SETTINGS,
            'Sitegeist.PaperTiger.CPX.asyncValidation.ruleProviders'
        ) ?? [];

        $classNames = array_values(array_filter($configured, static fn (mixed $value): bool => is_string($value) && $value !== ''));

        // Default provider comes last, so projects can override/replace rules by validationId.
        $classNames[] = DefaultAsyncValidationRuleProvider::class;

        $providers = [];
        foreach ($classNames as $className) {
            if (!class_exists($className)) {
                continue;
            }

            $instance = $this->objectManager->get($className);
            if ($instance instanceof AsyncValidationRuleProviderInterface) {
                $providers[] = $instance;
            }
        }

        usort(
            $providers,
            static fn (AsyncValidationRuleProviderInterface $a, AsyncValidationRuleProviderInterface $b): int => $b->getPriority() <=> $a->getPriority()
        );

        $this->providers = $providers;
        return $providers;
    }
}

