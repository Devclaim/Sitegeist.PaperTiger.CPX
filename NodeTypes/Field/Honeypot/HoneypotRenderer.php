<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Honeypot;

use Neos\Flow\Security\Cryptography\HashService;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\HoneypotField\HoneypotField;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\Components\Field\HoneypotField\HoneypotFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Resource\ResourceFactory;

final class HoneypotRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly HashService $hashService,
        private readonly ResourceFactory $resourceFactory,
        private readonly FieldContainerFactory $fieldContainerFactory,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $name = $context->nodes->getStringValue($context->node, 'name') ?? $context->node->aggregateId->value;

        if ($context->renderingMode->isEdit) {
            $fieldContainer = FieldContainerProps::create(
                id: 'fieldcontainer_' . $name,
                label: 'Honeypot',
                inputId: null,
                isRequired: false,
            );

            return $this->fieldContainerFactory->create(
                $context,
                null,
                label: $fieldContainer->label,
                inputId: $fieldContainer->inputId,
                isRequired: $fieldContainer->isRequired,
            );
        }

        $timestampWithHmac = $this->hashService->appendHmac((string)time());
        $scriptTargetId = 'field_' . $name;

        return HoneypotField::create(
            field: HoneypotFieldProps::create(
                name: $name,
                firstInputName: $name . '[one]',
                secondInputName: $name . '[two]',
                thirdInputName: $name . '[three]',
                scriptTargetId: $scriptTargetId,
                timestampWithHmac: $timestampWithHmac,
                style: 'display:none !important;',
            ),
            script: $this->resourceFactory->inlinePublicScript(
                'Sitegeist.PaperTiger.CPX',
                'Scripts/Honeypot.js',
                [
                    'data-name' => $scriptTargetId,
                    'data-value' => $timestampWithHmac,
                ],
            ),
        );
    }
}
