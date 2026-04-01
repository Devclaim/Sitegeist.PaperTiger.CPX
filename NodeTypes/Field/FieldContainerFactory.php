<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field;

use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\ComponentEngine\ComponentInterface;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainer;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\Components\Label\Label;
use Sitegeist\PaperTiger\CPX\Components\Label\LabelProps;

final class FieldContainerFactory
{
    public static function create(
        NeosContext $context,
        ComponentInterface|string|null $content,
        ?string $label = null,
        ?string $inputId = null,
        ?bool $isRequired = null,
    ): FieldContainer {
        $identifier = $context->nodes->getStringValue($context->node, 'name') ?? $context->node->aggregateId->value;
        $fieldContainer = FieldContainerProps::create(
            id: 'fieldcontainer_' . $identifier,
            label: $label ?? $context->nodes->getStringValue($context->node, 'label'),
            inputId: $inputId ?? 'field_' . $identifier,
            isRequired: $isRequired ?? $context->nodes->getBoolValue($context->node, 'isRequired'),
        );

        return FieldContainer::create(
            fieldContainer: $fieldContainer,
            label: Label::create(
                label: LabelProps::create(
                    inputId: $fieldContainer->inputId,
                    label: $fieldContainer->label,
                    isRequired: $fieldContainer->isRequired,
                ),
            ),
            content: $content,
        );
    }
}
