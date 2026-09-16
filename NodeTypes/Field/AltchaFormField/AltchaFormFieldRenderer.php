<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\AltchaFormField;

use Neos\Neos\NodeTypes\Document;
use Neos\Neos\NodeTypes\Site;
use PackageFactory\ComponentEngine\ComponentList;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\AltchaField\AltchaField;
use Sitegeist\PaperTiger\CPX\Components\Field\AltchaField\AltchaFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Resource\ResourceFactory;

/**
 * @implements ContentNodeRendererInterface<AltchaFormField,Document,Site>
 */
final class AltchaFormFieldRenderer implements ContentNodeRendererInterface
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
            ComponentList::list(
                AltchaField::create(
                    field: AltchaFieldProps::create(
                        name: $context->current->name,
                        /** @todo actually resolve URI */
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
