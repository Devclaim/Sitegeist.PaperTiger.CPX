<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Fieldset;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentRenderer;
use PackageFactory\Neos\ComponentEngine\Integration\RenderingUseCase;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\Neos\ComponentEngine\Presentation\Component\ContentElementCollectionItems;
use Sitegeist\PaperTiger\CPX\Components\Fieldset\Fieldset;
use Sitegeist\PaperTiger\CPX\Components\Fieldset\FieldsetProps;

final class FieldsetRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly ContentRenderer $contentRenderer,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $items = $this->contentRenderer->renderContentChildren($context, RenderingUseCase::CONTENT);

        return Fieldset::create(
            fieldset: FieldsetProps::create(
                id: 'fieldset_' . $context->node->aggregateId->value,
                label: $context->nodes->getStringValue($context->node, 'label'),
            ),
            content: ContentElementCollectionItems::create(
                editable: $context->renderingMode->isEdit,
                content: $items,
            ),
        );
    }
}
