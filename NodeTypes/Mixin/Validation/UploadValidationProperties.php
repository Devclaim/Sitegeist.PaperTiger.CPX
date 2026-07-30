<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation;

use PackageFactory\OPGM\Domain\Property\BackingTraitPropertyDefaultValueDeclaration;

/**
 * backing trait for {@see UploadValidationProvider}
 */
trait UploadValidationProperties
{
    #[BackingTraitPropertyDefaultValueDeclaration(defaultValue: [])]
    public readonly array $allowedExtensions;

    #[BackingTraitPropertyDefaultValueDeclaration(defaultValue: false)]
    public readonly bool $uploadTypeUseCustomMessage;

    public ?string $uploadTypeMessage;

    public ?int $allowedFilesize;

    #[BackingTraitPropertyDefaultValueDeclaration(defaultValue: false)]
    public readonly bool $uploadSizeUseCustomMessage;

    public ?string $uploadSizeMessage;
}
