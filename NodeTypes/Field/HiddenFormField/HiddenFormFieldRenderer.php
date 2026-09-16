<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\HiddenFormField;

use Neos\Neos\NodeTypes\Document;
use Neos\Neos\NodeTypes\Site;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenField;
use Sitegeist\PaperTiger\CPX\Components\Field\HiddenField\HiddenFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

/**
 * @implements ContentNodeRendererInterface<HiddenFormField,Document,Site>
 */
final class HiddenFormFieldRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly FieldContainerFactory $fieldContainerFactory,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $hiddenField = HiddenField::create(
            field: HiddenFieldProps::create(
                name: $context->current->name,
                value: $context->current->value,
                inBackend: $context->renderingMode->isEdit,
            ),
        );

        if (!$context->renderingMode->isEdit) {
            return $hiddenField;
        }

        return $this->fieldContainerFactory->create(
            context: $context,
            content: $hiddenField,
            label: $context->current->name,
            isRequired: false,
        );
    }
}
