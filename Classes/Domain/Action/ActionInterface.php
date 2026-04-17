<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Action;

use Neos\Flow\Mvc\ActionResponse;

interface ActionInterface
{
    public function perform(): ?ActionResponse;
}
