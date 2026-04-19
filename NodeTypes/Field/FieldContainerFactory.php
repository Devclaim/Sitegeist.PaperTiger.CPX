<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field;

use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\ComponentEngine\ComponentCollection;
use Sitegeist\PaperTiger\CPX\Components\Error\ErrorProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\Components\Label\LabelProps;
use Sitegeist\PaperTiger\CPX\Domain\PaperTigerFormState;

final class FieldContainerFactory
{
    public function __construct(
        private readonly FieldComponentFactory $fieldComponentFactory,
    ) {
    }

    public function create(
        NeosContext $context,
        ComponentInterface|string|null $content,
        ?string $label = null,
        ?string $inputId = null,
        ?bool $isRequired = null,
    ): ComponentInterface {
        $identifier = $context->nodes->getStringValue($context->node, 'name') ?? $context->node->aggregateId->value;
        $formState = PaperTigerFormState::fromRequest($context->request);
        $errors = $formState?->getErrorsFor($identifier) ?? [];
        $fieldContainer = FieldContainerProps::create(
            id: 'fieldcontainer_' . $identifier,
            label: $label ?? $context->nodes->getStringValue($context->node, 'label'),
            inputId: $inputId ?? 'field_' . $identifier,
            isRequired: $isRequired ?? $context->nodes->getBoolValue($context->node, 'isRequired'),
            hasErrors: $errors !== [],
        );

        return $this->fieldComponentFactory->createFieldContainer(
            fieldContainer: $fieldContainer,
            label: $this->fieldComponentFactory->createLabel(
                label: LabelProps::create(
                    inputId: $fieldContainer->inputId,
                    label: $fieldContainer->label,
                    isRequired: $fieldContainer->isRequired,
                ),
            ),
            content: $content,
            error: $errors !== []
                ? ComponentCollection::list(...array_map(
                    fn ($error) => $this->fieldComponentFactory->createError(
                        ErrorProps::create(message: $error->message),
                    ),
                    $errors
                ))
                : null,
        );
    }
}
