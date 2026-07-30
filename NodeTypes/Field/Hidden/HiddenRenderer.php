<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Hidden;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\OPGM\Domain\ObjectPropertyGraphMapper;
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
        $formField = ObjectPropertyGraphMapper::map($context->node, $context->subgraph, Hidden::class);

        $hiddenField = HiddenField::create(
            field: HiddenFieldProps::create(
                name: $formField->name,
                value: $formField->value,
                inBackend: $context->renderingMode->isEdit,
            ),
        );

        if (!$context->renderingMode->isEdit) {
            return $hiddenField;
        }

        return $this->fieldContainerFactory->create(
            context: $context,
            content: $hiddenField,
            label: $formField->name,
            isRequired: false,
        );
    }
}
