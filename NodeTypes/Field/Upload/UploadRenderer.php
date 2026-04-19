<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Upload;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\PaperTigerFormState;
use Sitegeist\PaperTiger\CPX\Components\Field\UploadField\UploadFieldProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

final class UploadRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly FieldContainerFactory $fieldContainerFactory,
        private readonly FieldComponentFactory $fieldComponentFactory,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $name = $context->nodes->getStringValue($context->node, 'name') ?? $context->node->aggregateId->value;
        $formState = PaperTigerFormState::fromRequest($context->request);
        $allowedExtensions = $context->node->getProperty('allowedExtensions');
        $allowedFilesize = $context->nodes->getIntValue($context->node, 'allowedFilesize');
        $accept = null;
        if (is_array($allowedExtensions) && $allowedExtensions !== []) {
            $extensionToMime = [
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'tiff' => 'image/tiff',
                'pdf' => 'application/pdf',
                'csv' => 'text/csv',
                'zip' => 'application/zip',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'odt' => 'application/vnd.oasis.opendocument.text',
            ];

            $acceptItems = [];
            foreach ($allowedExtensions as $item) {
                if (!is_string($item) || $item === '') {
                    continue;
                }
                $ext = strtolower($item);
                $acceptItems[] = $extensionToMime[$ext] ?? ('.' . $ext);
            }

            $acceptItems = array_values(array_unique($acceptItems));
            if ($acceptItems !== []) {
                $accept = implode(', ', $acceptItems);
            }
        }
        $fieldContainer = FieldContainerProps::create(
            id: 'fieldcontainer_' . $name,
            label: $context->nodes->getStringValue($context->node, 'label'),
            inputId: 'field_' . $name,
            isRequired: $context->nodes->getBoolValue($context->node, 'isRequired'),
            hasErrors: $formState?->hasErrorsFor($name),
        );

        return $this->fieldContainerFactory->create(
            $context,
            $this->fieldComponentFactory->createUpload(
                field: UploadFieldProps::create(
                    fieldContainer: $fieldContainer,
                    name: $name,
                    isMultiple: $context->nodes->getBoolValue($context->node, 'isMultiple'),
                    isRequired: $context->nodes->getBoolValue($context->node, 'isRequired'),
                    accept: $accept,
                    allowedExtensions: is_array($allowedExtensions) ? implode(',', $allowedExtensions) : null,
                    allowedFilesize: $allowedFilesize,
                    customErrorMessageEnabled: $context->nodes->getBoolValue($context->node, 'customErrorMessageEnabled'),
                    customErrorMessage: $context->nodes->getStringValue($context->node, 'customErrorMessage'),
                ),
            ),
        );
    }
}
