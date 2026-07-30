<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Upload;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\Neos\ComponentEngine\Integration\ContentNodeRendererInterface;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use PackageFactory\OPGM\Domain\ObjectPropertyGraphMapper;
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
        $formField = ObjectPropertyGraphMapper::map($context->node, $context->subgraph, Upload::class);
        $formState = PaperTigerFormState::fromRequest($context->request);
        $accept = null;
        if ($formField->allowedExtensions !== []) {
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
            foreach ($formField->allowedExtensions as $item) {
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
            id: 'fieldcontainer_' . $formField->name,
            label: $formField->label,
            inputId: 'field_' . $formField->name,
            isRequired: $formField->isRequired,
            hasErrors: $formState?->hasErrorsFor($formField->name),
        );

        return $this->fieldContainerFactory->create(
            $context,
            $this->fieldComponentFactory->createUpload(
                field: UploadFieldProps::create(
                    fieldContainer: $fieldContainer,
                    name: $formField->isMultiple ? $formField->name . '[]' : $formField->name,
                    isMultiple: $formField->isMultiple,
                    isRequired: $formField->isRequired,
                    accept: $accept,
                    allowedExtensions: implode(',', $formField->allowedExtensions),
                    allowedFilesize: $formField->allowedFilesize,
                    customErrorMessageEnabled: $formField->customErrorMessageEnabled,
                    customErrorMessage: $formField->customErrorMessage,
                ),
            ),
        );
    }
}
