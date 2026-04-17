<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Action;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\ActionResponse;
use Psr\Http\Message\UriFactoryInterface;

final class RedirectAction extends AbstractAction
{
    #[Flow\Inject]
    protected UriFactoryInterface $uriFactory;

    public function perform(): ?ActionResponse
    {
        $uri = $this->options['uri'] ?? null;
        if (!is_string($uri) || $uri === '') {
            throw new \RuntimeException('No uri for redirect action was defined.', 1583249244);
        }

        $status = (int)($this->options['status'] ?? 303);
        $response = new ActionResponse();
        $response->setRedirectUri($this->uriFactory->createUri($uri), $status);

        return $response;
    }
}
