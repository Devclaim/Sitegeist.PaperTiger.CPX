<?php
declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Validation\Validator;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Validation\Validator\AbstractValidator;
use Sitegeist\PaperTiger\CPX\Infrastructure\AltchaService;

class AltchaValidator extends AbstractValidator
{
    #[Flow\Inject]
    protected AltchaService $altchaService;

    protected function isValid($value)
    {
        if (empty($value) || !is_string($value)) {
            $this->addError('Please complete the CAPTCHA.', 1623456780);
            return;
        }

        $ok = $this->altchaService->verify($value);

        if (!$ok) {
            $this->addError('Verification failed — you may be a bot.', 1623456781);
        }
    }
}
