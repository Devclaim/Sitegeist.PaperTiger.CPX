<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\UploadFormField;

use Neos\Neos\NodeTypes\Document;
use Neos\Neos\NodeTypes\Site;
use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\PaperTigerFormState;
use Sitegeist\PaperTiger\CPX\Components\Field\UploadField\UploadFieldProps;
use Sitegeist\PaperTiger\CPX\Components\FieldContainer\FieldContainerProps;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldComponentFactory;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldContainerFactory;

/**
 * @implements ContentNodeRendererInterface<UploadFormField,Document,Site>
 */
final class UploadFormFieldRenderer implements ContentNodeRendererInterface
{
    public function __construct(
        private readonly FieldContainerFactory $fieldContainerFactory,
        private readonly FieldComponentFactory $fieldComponentFactory,
    ) {
    }

    public function renderAsContent(NeosContext $context): ComponentInterface
    {
        $formState = PaperTigerFormState::fromRequest($context->request);
        $accept = null;
        if ($context->current->allowedExtensions !== []) {
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
            foreach ($context->current->allowedExtensions as $item) {
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
            id: 'fieldcontainer_' . $context->current->name,
            label: $context->current->label,
            inputId: 'field_' . $context->current->name,
            isRequired: $context->current->isRequired,
            hasErrors: $formState?->hasErrorsFor($context->current->name),
        );

        return $this->fieldContainerFactory->create(
            $context,
            $this->fieldComponentFactory->createUpload(
                field: UploadFieldProps::create(
                    fieldContainer: $fieldContainer,
                    name: $context->current->isMultiple ? $context->current->name . '[]' : $context->current->name,
                    isMultiple: $context->current->isMultiple,
                    isRequired: $context->current->isRequired,
                    accept: $accept,
                    allowedExtensions: implode(',', $context->current->allowedExtensions),
                    allowedFilesize: $context->current->allowedFilesize,
                    customErrorMessageEnabled: $context->current->customErrorMessageEnabled,
                    customErrorMessage: $context->current->customErrorMessage,
                ),
            ),
        );
    }
}
