<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Honeypot;

use Neos\Flow\Security\Cryptography\HashService;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\Components\Field\HoneypotField\HoneypotFieldProps;
use Sitegeist\PaperTiger\CPX\Components\Label\LabelProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\AbstractFieldRenderer;
use Sitegeist\PaperTiger\CPX\NodeTypes\Resource\ResourceFactory;

final class HoneypotRenderer extends AbstractFieldRenderer
{
    public function __construct(
        private readonly HashService $hashService,
        private readonly ResourceFactory $resourceFactory,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $name = $this->identifier($context);

        if ($context->renderingMode->isEdit) {
            $fieldContainer = FieldContainerProps::create(
                id: 'fieldcontainer_' . $name,
                label: 'Honeypot',
                inputId: null,
                isRequired: false,
            );

            return $this->createComponent(
                $this->components()->fieldContainerComponent(),
                [
                    'fieldContainer' => $fieldContainer,
                    'label' => $this->createComponent(
                        $this->components()->labelComponent(),
                        [
                            'label' => LabelProps::create(
                                inputId: $fieldContainer->inputId,
                                label: $fieldContainer->label,
                                isRequired: $fieldContainer->isRequired,
                            ),
                        ],
                    ),
                    'content' => null,
                ],
            );
        }

        $timestampWithHmac = $this->hashService->appendHmac((string)time());
        $scriptTargetId = 'field_' . $name;

        return $this->createComponent(
            $this->components()->honeypotFieldComponent(),
            [
                'field' => HoneypotFieldProps::create(
                    name: $name,
                    firstInputName: $name . '[one]',
                    secondInputName: $name . '[two]',
                    thirdInputName: $name . '[three]',
                    scriptTargetId: $scriptTargetId,
                    timestampWithHmac: $timestampWithHmac,
                    style: 'display:none !important;',
                ),
                'script' => $this->resourceFactory->inlinePublicScript(
                    'Sitegeist.PaperTiger.CPX',
                    'Scripts/Honeypot.js',
                    [
                        'data-name' => $scriptTargetId,
                        'data-value' => $timestampWithHmac,
                    ],
                ),
            ],
        );
    }
}
