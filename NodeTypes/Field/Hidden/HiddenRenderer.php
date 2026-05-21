<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Hidden;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenField;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

final class HiddenRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly FieldContainerFactory $fieldContainerFactory,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $name = $context->nodes->getStringValue($context->node, 'name') ?? $context->node->aggregateId->value;
        $isEdit = $context->renderingMode->isEdit;

        $hiddenField = HiddenField::create(
            field: HiddenFieldProps::create(
                name: $name,
                value: $context->nodes->getStringValue($context->node, 'value'),
                inBackend: $isEdit,
            ),
        );

        if (!$isEdit) {
            return $hiddenField;
        }

        return $this->fieldContainerFactory->create(
            $context,
            $hiddenField,
            label: $name,
            isRequired: false,
        );
    }
}
