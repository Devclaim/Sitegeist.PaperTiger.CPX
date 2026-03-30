<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Fieldset;

use Neos\Flow\Annotations as Flow;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentRenderer;
use PackageFactory\Neos\ComponentEngine\Integration\RenderingUseCase;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\Neos\ComponentEngine\Presentation\Component\ContentElementCollectionItems;
use Sitegeist\PaperTiger\CPX\Components\Fieldset\FieldsetProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Form\FormComponentRegistry;

final class FieldsetRenderer implements ContentNodeRendererInterface
{
    #[Flow\Inject]
    protected FormComponentRegistry $formComponentRegistry;

    public function __construct(
        private readonly ContentRenderer $contentRenderer,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $items = $this->contentRenderer->renderContentChildren($context, RenderingUseCase::CONTENT);

        return $this->formComponentRegistry->current()->fieldsetComponent()::create(
            fieldset: FieldsetProps::create(
                id: 'fieldset_' . $context->node->aggregateId->value,
                label: is_scalar($context->node->getProperty('label')) ? (string)$context->node->getProperty('label') : null,
            ),
            content: ContentElementCollectionItems::create(
                editable: $context->renderingMode->isEdit,
                content: $items,
            ),
        );
    }
}
