<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Action;

interface ConfigurableActionInterface extends ActionInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function withOptions(array $options = []): self;
}
