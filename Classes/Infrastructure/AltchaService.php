<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Infrastructure;

use Neos\Flow\Annotations as Flow;
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
    #[Flow\InjectConfiguration(path: 'Altcha.secret', package: 'Sitegeist.PaperTiger.CPX')]
    protected string $secret;

    private readonly Altcha $altchaClient;
    private readonly Pbkdf2 $algorithm;

    public function initializeObject(): void
    {
        $this->altchaClient = new Altcha($this->secret);
        $this->algorithm = new Pbkdf2();
    }

    public function createChallenge(?int $cost = null, ?\DateInterval $expires = null): Challenge
    {
        $cost = $cost ?? 50000;
        $expires = $expires ?? new \DateInterval('PT5M');

        $options = new CreateChallengeOptions(
            algorithm: $this->algorithm,
            cost: $cost,
            expiresAt: (new \DateTimeImmutable())->add($expires),
        );

        return $this->altchaClient->createChallenge($options);
    }

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

            return $result->verified;
        } catch (\Throwable) {
            return false;
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
