<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Resource;

use PackageFactory\ComponentEngine\ComponentInterface;
use PackageFactory\ComponentEngine\StringComponent;

final class ResourceFactory
{
    /**
     * @param array<string, scalar|bool|null> $attributes
     */
    public function inlinePublicScript(
        string $packageKey,
        string $relativePathAndFilename,
        array $attributes = [],
    ): ?ComponentInterface {
        $script = $this->publicAssetContents($packageKey, $relativePathAndFilename);
        if ($script === null) {
            return null;
        }

        return StringComponent::fromHtmlString(
            sprintf(
                '<script%s>%s</script>',
                $this->htmlAttributesArrayToString($attributes),
                $script,
            ),
        );
    }

    public function inlinePublicStyle(
        string $packageKey,
        string $relativePathAndFilename,
    ): ?ComponentInterface {
        $css = $this->publicAssetContents($packageKey, $relativePathAndFilename);
        if ($css === null) {
            return null;
        }

        return StringComponent::fromHtmlString(
            sprintf('<style>%s</style>', $css),
        );
    }

    public function publicAssetContents(
        string $packageKey,
        string $relativePathAndFilename,
    ): ?string {
        $content = file_get_contents(
            sprintf(
                'resource://%s/Public/%s',
                $packageKey,
                ltrim($relativePathAndFilename, '/'),
            ),
        );

        return is_string($content) ? $content : null;
    }

    /**
     * @param array<string, scalar|bool|null> $attributes
     */
    private function htmlAttributesArrayToString(array $attributes): string
    {
        $parts = [];

        foreach ($attributes as $key => $value) {
            if ($value === null || $value === false) {
                continue;
            }

            if ($value === true) {
                $parts[] = htmlspecialchars($key, ENT_QUOTES);
                continue;
            }

            $parts[] = sprintf(
                '%s="%s"',
                htmlspecialchars($key, ENT_QUOTES),
                htmlspecialchars((string)$value, ENT_QUOTES),
            );
        }

        return $parts === [] ? '' : ' ' . implode(' ', $parts);
    }
}
