<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Action;

use Neos\Flow\Mvc\ActionRequest;

/**
 * PaperTiger "message" follow-up action.
 *
 * Unlike redirect/email this does not return an HTTP response. It stores a message
 * on the current request so the form renderer can replace the form with a message component.
 */
final class MessageAction
{
    public const REQUEST_ARGUMENT_MESSAGE = '__paperTigerMessage';

    public function perform(ActionRequest $request, ?string $message): void
    {
        if (!is_string($message) || $message === '') {
            return;
        }

        $existing = $request->getInternalArgument(self::REQUEST_ARGUMENT_MESSAGE);
        if (is_string($existing) && $existing !== '') {
            $message = $existing . "\n" . $message;
        }

        $request->setArgument(self::REQUEST_ARGUMENT_MESSAGE, $message);
    }
}

