<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Fieldset;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentRenderer;
use PackageFactory\Neos\ComponentEngine\Integration\RenderingUseCase;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\Neos\ComponentEngine\Presentation\Component\ContentElementCollectionItems;
use PackageFactory\OPGM\Domain\ObjectPropertyGraphMapper;
use Sitegeist\PaperTiger\CPX\Components\Fieldset\Fieldset as FieldsetComponent;
use Sitegeist\PaperTiger\CPX\Components\Fieldset\FieldsetProps;

final class FieldsetRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly ContentRenderer $contentRenderer,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $fieldSet = ObjectPropertyGraphMapper::map($context->node, $context->subgraph, Fieldset::class);

        return FieldsetComponent::create(
            fieldset: FieldsetProps::create(
                id: 'fieldset_' . $fieldSet->node->aggregateId->value,
                label: $fieldSet->label,
            ),
            content: ContentElementCollectionItems::create(
                editable: $context->renderingMode->isEdit,
                content: $this->contentRenderer->renderContentChildren($context, RenderingUseCase::CONTENT),
            ),
        );
    }
}
