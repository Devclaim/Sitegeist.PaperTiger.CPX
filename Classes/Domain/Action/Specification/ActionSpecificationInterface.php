<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Action\Specification;

/**
 * Future contract for property-based follow-up actions on forms.
 *
 * These specifications mirror the currently supported action child nodes so we
 * can move toward custom action editors without losing a typed PHP contract.
 */
interface ActionSpecificationInterface extends \JsonSerializable
{
    public function type(): string;

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array;
}
