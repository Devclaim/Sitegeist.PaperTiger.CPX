<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Button;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\ButtonField\ButtonFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;

final class ButtonRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly FieldComponentFactory $fieldComponentFactory,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return $this->fieldComponentFactory->createButton(
            ButtonFieldProps::create(
                label: $context->nodes->getStringValue($context->node, 'label'),
            )
        );
    }
}
