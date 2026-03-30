<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Form;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
final class FormRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly FormFactory $formFactory,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return $this->formFactory->create($context);
    }
}
