<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Upload;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Components\Field\UploadField\UploadFieldProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\AbstractFieldRenderer;

final class UploadRenderer extends AbstractFieldRenderer
{
    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $allowedExtensions = $context->node->getProperty('allowedExtensions');

        return $this->wrapField($context, $this->createComponent(
            $this->components()->uploadFieldComponent(),
            [
                'field' => UploadFieldProps::create(
                    fieldContainer: $this->createFieldContainerProps($context),
                    name: $this->nameOrIdentifier($context),
                    isMultiple: $this->boolProperty($context->node, 'isMultiple'),
                    isRequired: $this->boolProperty($context->node, 'isRequired'),
                    allowedExtensions: is_array($allowedExtensions) ? implode(',', array_map('strval', $allowedExtensions)) : null,
                    allowedFilesize: $this->intProperty($context->node, 'allowedFilesize'),
                    customErrorMessageEnabled: $this->boolProperty($context->node, 'customErrorMessageEnabled'),
                    customErrorMessage: $this->stringProperty($context->node, 'customErrorMessage'),
                ),
            ],
        ));
    }
}
