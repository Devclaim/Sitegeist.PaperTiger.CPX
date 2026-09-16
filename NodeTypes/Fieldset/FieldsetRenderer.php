<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Fieldset;

use Neos\Neos\NodeTypes\Document;
use Neos\Neos\NodeTypes\Site;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentRenderer;
use PackageFactory\Neos\ComponentEngine\Integration\RenderingUseCase;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\Neos\ComponentEngine\Presentation\Component\ContentElementListItems;
use Sitegeist\PaperTiger\CPX\Components\Fieldset\Fieldset as FieldsetComponent;
use Sitegeist\PaperTiger\CPX\Components\Fieldset\FieldsetProps;

/**
 * @implements ContentNodeRendererInterface<Fieldset,Document,Site>
 */
final class FieldsetRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly ContentRenderer $contentRenderer,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return FieldsetComponent::create(
            fieldset: FieldsetProps::create(
                id: 'fieldset_' . $context->current->node->aggregateId->value,
                label: $context->current->label,
            ),
            content: ContentElementListItems::create(
                editable: $context->renderingMode->isEdit,
                content: $this->contentRenderer->renderContentChildren($context, RenderingUseCase::CONTENT),
            ),
        );
    }
}
