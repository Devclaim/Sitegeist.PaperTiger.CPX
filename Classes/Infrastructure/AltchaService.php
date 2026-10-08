<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Infrastructure;

use Neos\Cache\Frontend\StringFrontend;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Utility\Environment;
use AltchaOrg\Altcha\Algorithm\Pbkdf2;
use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\Challenge;
use AltchaOrg\Altcha\ChallengeParameters;
use AltchaOrg\Altcha\CreateChallengeOptions;
use AltchaOrg\Altcha\Payload;
use AltchaOrg\Altcha\Solution;
use AltchaOrg\Altcha\VerifySolutionOptions;

class AltchaService
{
    /** challenges without expiry (not created by this service) are remembered this long */
    private const DEFAULT_LIFETIME = 300;

    private readonly Altcha $altchaClient;
    private readonly Pbkdf2 $algorithm;

    #[Flow\Inject]
    protected Environment $environment;

    /**
     * @param StringFrontend $usedSolutions the signatures of solved challenges, see Caches.yaml
     */
    public function __construct(
        string $secret,
        private readonly StringFrontend $usedSolutions,
    ) {
        $this->altchaClient = new Altcha($secret);
        $this->algorithm = new Pbkdf2();
    }

    public function createChallenge(?int $cost = null, ?\DateInterval $expires = null): Challenge
    {
        $cost = $cost ?? 50000;
        $expires = $expires ?? new \DateInterval('PT5M');

        $options = new CreateChallengeOptions(
            algorithm: $this->algorithm,
            cost: $cost,
            expiresAt: new \DateTimeImmutable()->add($expires),
        );

        return $this->altchaClient->createChallenge($options);
    }

    /**
     * A valid solution of a challenge of this service, used for the first time: a solved challenge can be sent only
     * once (otherwise one solution would let a bot send a form again and again until the challenge expires)
     */
    public function verify(string $solution): bool
    {
        try {
            $payload = $this->decodePayload($solution);
            $result = $this->altchaClient->verifySolution(
                new VerifySolutionOptions(
                    payload: $payload,
                    algorithm: $this->algorithm,
                ),
            );
        } catch (\Throwable) {
            return false;
        }

        $signature = $payload->challenge->signature;
        if (!$result->verified || $signature === null) {
            return false;
        }

        return $this->markAsUsed($signature, $payload->challenge->parameters->expiresAt);
    }

    /**
     * Remembers the signature until the challenge expires; false if it was used before. Checking and remembering
     * happen under a lock, so of two requests with the same solution (on different PHP workers) only one gets it in.
     */
    private function markAsUsed(string $signature, int|float|null $expiresAt): bool
    {
        $identifier = sha1($signature);
        $lifetime = $expiresAt !== null ? max(1, (int)ceil($expiresAt - time())) : self::DEFAULT_LIFETIME;

        $lock = fopen($this->environment->getPathToTemporaryDirectory() . 'Sitegeist_PaperTiger_CPX_Altcha.lock', 'c');
        if ($lock === false || !flock($lock, LOCK_EX)) {
            return false;
        }
        try {
            if ($this->usedSolutions->has($identifier)) {
                return false;
            }
            $this->usedSolutions->set($identifier, '1', [], $lifetime);

            return true;
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    private function decodePayload(string $solution): Payload
    {
        $decoded = base64_decode($solution, true);
        if ($decoded === false) {
            throw new \InvalidArgumentException('Invalid ALTCHA payload encoding.');
        }

        $data = json_decode($decoded, true);
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Invalid ALTCHA payload JSON.');
        }

        $challengeArr = is_array($data['challenge'] ?? null) ? $data['challenge'] : null;
        $parametersArr = is_array($challengeArr['parameters'] ?? null) ? $challengeArr['parameters'] : null;
        $solutionArr = is_array($data['solution'] ?? null) ? $data['solution'] : null;

        if ($challengeArr === null || $parametersArr === null || $solutionArr === null) {
            throw new \InvalidArgumentException('Invalid ALTCHA payload structure.');
        }

        $challenge = new Challenge(
            parameters: ChallengeParameters::fromArray($parametersArr),
            signature: is_string($challengeArr['signature'] ?? null) ? $challengeArr['signature'] : null,
        );

        $counter = $solutionArr['counter'] ?? null;
        $derivedKey = $solutionArr['derivedKey'] ?? null;
        $time = $solutionArr['time'] ?? null;

        if (!is_int($counter) || !is_string($derivedKey)) {
            throw new \InvalidArgumentException('Invalid ALTCHA solution payload.');
        }

        $parsedTime = is_float($time) || is_int($time) ? (float) $time : null;

        return new Payload(
            challenge: $challenge,
            solution: new Solution(
                counter: $counter,
                derivedKey: $derivedKey,
                time: $parsedTime,
            ),
        );
    }
}
