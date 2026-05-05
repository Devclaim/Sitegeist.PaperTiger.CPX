<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Action\Specification;

use Neos\Flow\Annotations as Flow;

/**
 * Mirrors the current Sitegeist.PaperTiger.CPX:Action.Message child node.
 */
#[Flow\Proxy(false)]
final readonly class MessageActionSpecification implements ActionSpecificationInterface
{
    public function __construct(
        public string $message = '',
    ) {
    }

    public function type(): string
    {
        return 'message';
    }

    /**
     * @param array<string, mixed> $values
     */
    public static function fromArray(array $values): self
    {
        return new self(
            message: is_string($values['message'] ?? null) ? $values['message'] : '',
        );
    }

    /**
     * @return array{type: string, message: string}
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type(),
            'message' => $this->message,
        ];
    }
}
