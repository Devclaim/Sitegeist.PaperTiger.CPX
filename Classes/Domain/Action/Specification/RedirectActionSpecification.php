<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Action\Specification;

use Neos\Flow\Annotations as Flow;

/**
 * Mirrors the current Sitegeist.PaperTiger.CPX:Action.Redirect child node.
 */
#[Flow\Proxy(false)]
final readonly class RedirectActionSpecification implements ActionSpecificationInterface
{
    public function __construct(
        public ?string $uri = null,
    ) {
    }

    public function type(): string
    {
        return 'redirect';
    }

    /**
     * @param array<string, mixed> $values
     */
    public static function fromArray(array $values): self
    {
        return new self(
            uri: is_string($values['uri'] ?? null) ? $values['uri'] : null,
        );
    }

    /**
     * @return array{type: string, uri: ?string}
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type(),
            'uri' => $this->uri,
        ];
    }
}
