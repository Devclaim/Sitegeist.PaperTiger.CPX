<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Form;

use Neos\Flow\Annotations as Flow;

#[Flow\Scope('singleton')]
final class FormComponentRegistry
{
    /**
     * @var list<FormComponents>
     */
    private array $stack = [];

    /**
     * @template T
     * @param \Closure():T $callback
     * @return T
     */
    public function with(?FormComponents $components, \Closure $callback): mixed
    {
        $this->stack[] = $components ?? FormComponents::create();

        try {
            return $callback();
        } finally {
            array_pop($this->stack);
        }
    }

    public function current(): FormComponents
    {
        return $this->stack === []
            ? FormComponents::create()
            : $this->stack[array_key_last($this->stack)];
    }
}
