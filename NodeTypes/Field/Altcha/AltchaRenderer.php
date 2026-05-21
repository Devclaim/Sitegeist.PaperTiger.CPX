<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Altcha;

use PackageFactory\ComponentEngine\ComponentCollection;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\AltchaField\AltchaField;
use Sitegeist\PaperTiger\CPX\Components\Field\AltchaField\AltchaFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Resource\ResourceFactory;

final class AltchaRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly ResourceFactory $resourceFactory,
        private readonly FieldContainerFactory $fieldContainerFactory
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        return $this->fieldContainerFactory->create(
            $context,
            ComponentCollection::list(
                AltchaField::create(
                    field: AltchaFieldProps::create(
                        name: $context->nodes->getStringValue($context->node, 'name'),
                        challengeUrl: '/altcha',
                    ),
                ),
                $this->resourceFactory->publicScriptTag(
                    'Sitegeist.PaperTiger.CPX',
                    'Scripts/Altcha.js',
                ),
            ),
            withoutLabel: true
        );
    }
}
