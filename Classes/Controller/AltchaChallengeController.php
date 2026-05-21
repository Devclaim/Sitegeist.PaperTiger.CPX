<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Controller;

use Sitegeist\PaperTiger\CPX\Dto\AltchaChallengeParameters;
use Sitegeist\PaperTiger\CPX\Dto\AltchaChallengeResponse;
use Sitegeist\PaperTiger\CPX\Infrastructure\AltchaService;
use Sitegeist\SchemeOnYou\Application\OpenApiController;

class AltchaChallengeController extends OpenApiController
{
    public function __construct(
        private readonly AltchaService $altchaService,
    ) {
    }

    public function challengeAction(): AltchaChallengeResponse
    {
        $challenge = $this->altchaService->createChallenge();
        $parameters = $challenge->parameters;

        return new AltchaChallengeResponse(
            parameters: new AltchaChallengeParameters(
                algorithm: $parameters->algorithm,
                nonce: $parameters->nonce,
                salt: $parameters->salt,
                cost: $parameters->cost,
                keyLength: $parameters->keyLength,
                keyPrefix: $parameters->keyPrefix,
                keySignature: $parameters->keySignature,
                memoryCost: $parameters->memoryCost,
                parallelism: $parameters->parallelism,
                expiresAt: $parameters->expiresAt,
            ),
            signature: $challenge->signature,
        );
    }
}
