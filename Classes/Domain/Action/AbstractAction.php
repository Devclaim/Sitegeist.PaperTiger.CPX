<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Action;

abstract class AbstractAction implements ConfigurableActionInterface
{
    public function __construct()
    {
    }

    /**
     * @var array<string, mixed>
     */
    protected array $options = [];

    /**
     * @param array<string, mixed> $options
     */
    public function withOptions(array $options = []): static
    {
        $subject = clone $this;
        $subject->options = array_merge($subject->options, $options);

        return $subject;
    }
}
