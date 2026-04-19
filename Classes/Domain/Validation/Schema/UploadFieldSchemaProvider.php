<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Schema;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use PackageFactory\Neos\ComponentEngine\NeosContext;
use Sitegeist\PaperTiger\CPX\Domain\Validation\FlowValidationErrorCodes;
use Sitegeist\PaperTiger\CPX\Domain\Validation\SchemaInterface;
use Sitegeist\PaperTiger\CPX\Domain\Validation\Validator\UploadedFileCollectionValidator;
use Sitegeist\PaperTiger\CPX\Domain\Validation\Validator\UploadedFileValidator;

final class UploadFieldSchemaProvider extends AbstractFieldSchemaProvider
{
    public function supports(NeosContext $context, Node $fieldNode): bool
    {
        $nodeType = $context->nodes->tryGetNodeType($fieldNode);
        return $nodeType?->isOfType('Sitegeist.PaperTiger.CPX:Field.Upload') ?? false;
    }

    public function build(NeosContext $context, Node $fieldNode): ?SchemaInterface
    {
        $isMultiple = $context->nodes->getBoolValue($fieldNode, 'isMultiple') ?? false;
        $schema = $isMultiple ? $this->createSchema('array') : $this->createSchema('Psr\Http\Message\UploadedFileInterface');
        $this->applyRequiredValidation($context, $fieldNode, $schema);

        $options = array_filter([
            'allowedExtensions' => $fieldNode->getProperty('allowedExtensions'),
            'maximumSize' => $context->nodes->getIntValue($fieldNode, 'allowedFilesize'),
        ], static fn (mixed $value): bool => $value !== null && $value !== []);

        if ($options !== []) {
            $schema->validator(
                $isMultiple ? UploadedFileCollectionValidator::class : UploadedFileValidator::class,
                $options
            );
        }

        $useTypeMessage = $context->nodes->getBoolValue($fieldNode, 'uploadTypeUseCustomMessage')
            ?? $context->nodes->getBoolValue($fieldNode, 'useCustomUploadTypeMessage')
            ?? false;
        if ($useTypeMessage) {
            $message = $context->nodes->getStringValue($fieldNode, 'uploadTypeMessage');
            if (is_string($message) && $message !== '') {
                $schema->overrideErrorMessage(FlowValidationErrorCodes::UPLOAD_EXTENSION_NOT_ALLOWED, $message);
            }
        }

        $useSizeMessage = $context->nodes->getBoolValue($fieldNode, 'uploadSizeUseCustomMessage')
            ?? $context->nodes->getBoolValue($fieldNode, 'useCustomUploadSizeMessage')
            ?? false;
        if ($useSizeMessage) {
            $message = $context->nodes->getStringValue($fieldNode, 'uploadSizeMessage');
            if (is_string($message) && $message !== '') {
                $schema->overrideErrorMessage(FlowValidationErrorCodes::UPLOAD_SIZE_EXCEEDED, $message);
            }
        }

        return $schema;
    }
}
