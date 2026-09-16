<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Button;

use Neos\Neos\NodeTypes\Document;
use Neos\Neos\NodeTypes\Site;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\ButtonField\ButtonFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

/**
 * @implements ContentNodeRendererInterface<Button,Document,Site>
 */
final class ButtonRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly FieldComponentFactory $fieldComponentFactory,
        private readonly FieldContainerFactory $fieldContainerFactory
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return $this->fieldContainerFactory->create(
            $context,
            $this->fieldComponentFactory->createButton(
                ButtonFieldProps::create(
                    label: $context->current->label
                )
            ),
            withoutLabel: true
        );
    }
}
