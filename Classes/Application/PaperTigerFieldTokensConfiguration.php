<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Application;

use PackageFactory\OPGM\Domain\Property\PropertyConfigurationInterface;

#[\Attribute(\Attribute::TARGET_PROPERTY|\Attribute::TARGET_PARAMETER)]
final readonly class PaperTigerFieldTokensConfiguration implements PropertyConfigurationInterface
{
    /**
     * @return array<string,mixed>
     */
    public function getPropertyConfiguration(\ReflectionNamedType $propertyType): array
    {
        return [
            'ui' => [
                'inline' => [
                    'editorOptions' => [
                        'paperTigerFieldTokens' => [
                            'enabled' => true
                        ]
                    ]
                ]
            ]
        ];
    }
}
