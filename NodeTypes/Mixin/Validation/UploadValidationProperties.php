<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\Validation;

/**
 * backing trait for {@see UploadValidationProvider}
 */
trait UploadValidationProperties
{
    public function __construct(
        public readonly array $allowedExtensions = [],
        public readonly bool $uploadTypeUseCustomMessage = false,
        public readonly ?string $uploadTypeMessage = null,
        public readonly ?int $allowedFilesize = null,
        public readonly bool $uploadSizeUseCustomMessage = false,
        public readonly ?string $uploadSizeMessage = null,
    ) {
    }
}
