<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field;

use Neos\Neos\NodeTypes\Document;
use Neos\Neos\NodeTypes\Site;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\ComponentEngine\ComponentList;
use Sitegeist\PaperTiger\CPX\Components\Error\ErrorProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\Components\Label\LabelProps;
use Sitegeist\PaperTiger\CPX\Domain\PaperTigerFormState;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\Button\Button;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\LabelProvider;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation\RequiredValidationProvider;

final class FieldContainerFactory
{
    public function __construct(
        private readonly FieldComponentFactory $fieldComponentFactory,
    ) {
    }

    /**
     * @param NeosContext<FormField|Button,Document,Site> $context
     */
    public function create(
        NeosContext $context,
        ComponentInterface|string|null $content,
        ?string $label = null,
        ?string $inputId = null,
        ?bool $isRequired = null,
        ?bool $withoutLabel = false
    ): ComponentInterface {
        $identifier = $context->current instanceof FormField
            ? $context->current->name
            : $context->current->node->aggregateId->value;
        $formState = PaperTigerFormState::fromRequest($context->request);
        $errors = $formState?->getErrorsFor($identifier) ?? [];
        $fieldContainer = FieldContainerProps::create(
            id: 'fieldcontainer_' . $identifier,
            label: $label ?: ($context->current instanceof LabelProvider ? $context->current->label : null),
            inputId: $inputId ?: 'field_' . $identifier,
            isRequired: $isRequired !== null
                ? $isRequired
                : ($context->current instanceof RequiredValidationProvider ? $context->current->isRequired : false),
            hasErrors: $errors !== [],
        );

        return $this->fieldComponentFactory->createFieldContainer(
            fieldContainer: $fieldContainer,
            label: $withoutLabel ? null : $this->fieldComponentFactory->createLabel(
                label: LabelProps::create(
                    inputId: $fieldContainer->inputId,
                    label: $fieldContainer->label,
                    isRequired: $fieldContainer->isRequired,
                ),
            ),
            content: $content,
            error: $errors !== []
                ? ComponentList::list(...array_map(
                    fn ($error) => $this->fieldComponentFactory->createError(
                        ErrorProps::create(message: $error->message),
                    ),
                    $errors
                ))
                : null,
        );
    }
}
