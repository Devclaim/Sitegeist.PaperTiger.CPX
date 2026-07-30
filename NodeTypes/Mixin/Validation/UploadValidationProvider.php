<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation;

use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\InspectorGroupDeclaration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\SelectBoxEditor\SelectBoxEditorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\TextAreaEditor\TextAreaEditorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\InspectorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\PropertyUiConfiguration;

#[NodeTypeDeclaration]
#[InspectorGroupDeclaration(
    name: 'form-validation-upload-type',
    label: 'Sitegeist.PaperTiger.CPX:Main:validation.upload.type.group',
    icon: 'icon-upload',
    position: '50',
    tab: 'form-validation',
)]
#[InspectorGroupDeclaration(
    name: 'form-validation-upload-size',
    label: 'Sitegeist.PaperTiger.CPX:Main:validation.upload.size.group',
    icon: 'icon-upload',
    position: '60',
    tab: 'form-validation',
)]
interface UploadValidationProvider
{
    /**
     * @var string[]
     */
    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.upload.allowedExtensions',
        reloadIfChanged: true,
    )]
    #[InspectorConfiguration(group: 'form-validation-upload-type')]
    #[SelectBoxEditorConfiguration(
        values: [
            'jpeg' => [
                'label' => '.jpeg',
                'icon' => 'icon-image',
                'group' => 'Image',
            ],
            'jpg' => [
                'label' => '.jpg',
                'icon' => 'icon-image',
                'group' => 'Image',
            ],
            'png' => [
                'label' => '.png',
                'icon' => 'icon-image',
                'group' => 'Image',
            ],
            'gif' => [
                'label' => '.gif',
                'icon' => 'icon-image',
                'group' => 'Image',
            ],
            'tiff' => [
                'label' => '.tiff',
                'icon' => 'icon-image',
                'group' => 'Image',
            ],
            'xls' => [
                'label' => '.xls',
                'icon' => 'icon-file-excel-o',
                'group' => 'Office',
            ],
            'xlsx' => [
                'label' => '.xlsx',
                'icon' => 'icon-file-excel-o',
                'group' => 'Office',
            ],
            'doc' => [
                'label' => '.doc',
                'icon' => 'icon-file-text',
                'group' => 'Office',
            ],
            'docx' => [
                'label' => '.docx',
                'icon' => 'icon-file-text',
                'group' => 'Office',
            ],
            'odt' => [
                'label' => '.odt',
                'icon' => 'icon-file-text',
                'group' => 'Office',
            ],
            'pdf' => [
                'label' => '.pdf',
                'icon' => 'icon-file-pdf-o',
                'group' => 'Other',
            ],
            'csv' => [
                'label' => '.csv',
                'icon' => 'icon-file-text',
                'group' => 'Other',
            ],
            'zip' => [
                'label' => '.zip',
                'icon' => 'icon-archive',
                'group' => 'Other',
            ],
        ],
    )]
    public array $allowedExtensions {get;}

    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.useCustomMessage',
    )]
    #[InspectorConfiguration(group: 'form-validation-upload-type', position: 20)]
    public bool $uploadTypeUseCustomMessage {get;}

    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.upload.type.message',
    )]
    #[InspectorConfiguration(
        group: 'form-validation-upload-type',
        position: 30,
        hidden: 'ClientEval:node.properties.uploadTypeUseCustomMessage ? false : true',
    )]
    #[TextAreaEditorConfiguration(rows: 3)]
    public ?string $uploadTypeMessage {get;}

    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.upload.allowedFilesize',
        reloadIfChanged: true,
    )]
    #[InspectorConfiguration(group: 'form-validation-upload-size')]
    #[SelectBoxEditorConfiguration(
        values: [
            10000 => [
                'label' => '10kb',
            ],
            50000 => [
                'label' => '50kb',
            ],
            100000 => [
                'label' => '100kb',
            ],
            200000 => [
                'label' => '200kb',
            ],
            300000 => [
                'label' => '300kb',
            ],
            400000 => [
                'label' => '500kb',
            ],
            800000 => [
                'label' => '800kb',
            ],
            1000000 => [
                'label' => '1MB',
            ],
            21000000 => [
                'label' => '2MB',
            ],
            3000000 => [
                'label' => '3MB',
            ],
            5000000 => [
                'label' => '5MB',
            ],
            8000000 => [
                'label' => '8MB',
            ],
            10000000 => [
                'label' => '10MB',
            ],
        ],
        allowEmpty: true,
    )]
    public ?int $allowedFilesize {get;}

    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.useCustomMessage',
    )]
    #[InspectorConfiguration(
        group: 'form-validation-upload-size',
        position: 20,
    )]
    public bool $uploadSizeUseCustomMessage {get;}

    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:Main:validation.upload.size.message',
    )]
    #[InspectorConfiguration(
        group: 'form-validation-upload-size',
        position: 30,
        hidden: 'ClientEval:node.properties.uploadSizeUseCustomMessage ? false : true'
    )]
    #[TextAreaEditorConfiguration(rows: 3)]
    public ?string $uploadSizeMessage {get;}
}

