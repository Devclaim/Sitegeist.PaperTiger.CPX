<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Button;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\OPGM\Domain\ObjectPropertyGraphMapper;
use Sitegeist\PaperTiger\CPX\Components\Field\ButtonField\ButtonFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

final class ButtonRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly FieldComponentFactory $fieldComponentFactory,
        private readonly FieldContainerFactory $fieldContainerFactory
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $button = ObjectPropertyGraphMapper::map($context->node, $context->subgraph, Button::class);

        return $this->fieldContainerFactory->create(
            $context,
            $this->fieldComponentFactory->createButton(
                ButtonFieldProps::create(
                    label: $button->label
                )
            ),
            withoutLabel: true
        );
    }
}
