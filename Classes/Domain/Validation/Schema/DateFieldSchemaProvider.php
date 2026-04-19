<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\Flow\Validation\Validator\DateTimeRangeValidator;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\FlowValidationErrorCodes;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaInterface;

final class DateFieldSchemaProvider extends AbstractFieldSchemaProvider
{
    public function build(NeosContext $context, Node $fieldNode): ?SchemaInterface
    {
        $schema = $this->createSchema(\DateTimeImmutable::class);
        $this->applyRequiredValidation($context, $fieldNode, $schema);

        $options = array_filter([
            'earliestDate' => $this->formatDateOption($fieldNode, 'earliestDate'),
            'latestDate' => $this->formatDateOption($fieldNode, 'latestDate'),
        ], static fn (mixed $value): bool => $value !== null);

        if ($options !== []) {
            $schema->validator(DateTimeRangeValidator::class, $options);
        }

        $useCustom = $context->nodes->getBoolValue($fieldNode, 'dateRangeUseCustomMessage')
            ?? $context->nodes->getBoolValue($fieldNode, 'useCustomDateRangeMessage')
            ?? false;
        if ($useCustom) {
            $message = $context->nodes->getStringValue($fieldNode, 'dateRangeMessage');
            if (is_string($message) && $message !== '') {
                $schema->overrideErrorMessage(FlowValidationErrorCodes::DATE_TIME_RANGE_BETWEEN, $message);
                $schema->overrideErrorMessage(FlowValidationErrorCodes::DATE_TIME_RANGE_AFTER, $message);
                $schema->overrideErrorMessage(FlowValidationErrorCodes::DATE_TIME_RANGE_BEFORE, $message);
            }
        }

        return $schema;
    }

    private function formatDateOption(Node $node, string $propertyName): ?string
    {
        $value = $node->getProperty($propertyName);
        if (!$value instanceof \DateTimeInterface) {
            return null;
        }

        return $value->format('Y-m-d');
    }
}
