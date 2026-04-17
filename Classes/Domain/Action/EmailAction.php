<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Domain\Action;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\ActionResponse;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\ResourceManagement\PersistentResource;
use Neos\Utility\MediaTypes;
use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

final class EmailAction extends AbstractAction
{
    #[Flow\Inject]
    protected ObjectManagerInterface $objectManager;

    public function perform(): ?ActionResponse
    {
        $mailerServiceClassName = 'Neos\\SymfonyMailer\\Service\\MailerService';
        if (!class_exists($mailerServiceClassName)) {
            throw new \RuntimeException('The package "neos/symfonymailer" is required for the EmailAction to work.', 1503392532);
        }

        $subject = $this->requireStringOption('subject');
        $recipientAddress = $this->options['recipientAddress'] ?? null;
        $recipientName = (string)($this->options['recipientName'] ?? '');
        $senderAddress = $this->options['senderAddress'] ?? null;
        $senderName = (string)($this->options['senderName'] ?? '');

        if ($recipientAddress === null) {
            throw new \RuntimeException('The option "recipientAddress" must be set for the EmailAction.', 1327060200);
        }
        if (is_array($recipientAddress) && $recipientName !== '') {
            throw new \RuntimeException('The option "recipientName" cannot be used with multiple recipients.', 1483365977);
        }
        if (!is_string($senderAddress) || $senderAddress === '') {
            throw new \RuntimeException('The option "senderAddress" must be set for the EmailAction.', 1327060210);
        }

        $mail = (new Email())
            ->addFrom(new Address($senderAddress, $senderName))
            ->subject($subject);

        if (is_array($recipientAddress)) {
            $mail->addTo(...array_map(static fn (string $entry): Address => new Address($entry), $recipientAddress));
        } else {
            $mail->addTo(new Address((string)$recipientAddress, $recipientName));
        }

        $this->applyOptionalRecipients($mail);
        $this->applyBody($mail);
        $this->addAttachments($mail);

        if (($this->options['testMode'] ?? false) === true) {
            $response = new ActionResponse();
            $response->setContent(
                \Neos\Flow\var_dump(
                    [
                        'subject' => $subject,
                        'sender' => [$senderAddress => $senderName],
                        'recipients' => is_array($recipientAddress) ? $recipientAddress : [$recipientAddress => $recipientName],
                        'text' => $this->options['text'] ?? null,
                        'html' => $this->options['html'] ?? null,
                    ],
                    'E-Mail "' . $subject . '"',
                    true
                )
            );

            return $response;
        }

        /** @phpstan-ignore-next-line */
        $this->objectManager->get($mailerServiceClassName)->getMailer()->send($mail);

        return null;
    }

    private function applyOptionalRecipients(Email $mail): void
    {
        $replyToAddress = $this->options['replyToAddress'] ?? null;
        if (is_string($replyToAddress) && $replyToAddress !== '') {
            $mail->addReplyTo(new Address($replyToAddress));
        }

        $carbonCopyAddress = $this->options['carbonCopyAddress'] ?? null;
        if (is_string($carbonCopyAddress) && $carbonCopyAddress !== '') {
            $mail->addCc(new Address($carbonCopyAddress));
        }

        $blindCarbonCopyAddress = $this->options['blindCarbonCopyAddress'] ?? null;
        if (is_string($blindCarbonCopyAddress) && $blindCarbonCopyAddress !== '') {
            $mail->addBcc(new Address($blindCarbonCopyAddress));
        }
    }

    private function applyBody(Email $mail): void
    {
        $text = $this->options['text'] ?? null;
        $html = $this->options['html'] ?? null;

        if (is_string($text) && $text !== '' && is_string($html) && $html !== '') {
            $mail->html($html);
            $mail->text($text);
            return;
        }

        if (is_string($text) && $text !== '') {
            $mail->text($text);
            return;
        }

        if (is_string($html) && $html !== '') {
            $mail->html($html);
        }
    }

    private function addAttachments(Email $mail): void
    {
        $attachments = $this->options['attachments'] ?? null;
        if (!is_array($attachments)) {
            return;
        }

        foreach ($attachments as $attachment) {
            if (is_string($attachment)) {
                $mail->addPart(new DataPart(new File($attachment)));
                continue;
            }

            if ($attachment instanceof UploadedFileInterface) {
                $mail->addPart(new DataPart(
                    $attachment->getStream()->getContents(),
                    $attachment->getClientFilename(),
                    $attachment->getClientMediaType(),
                ));
                continue;
            }

            if ($attachment instanceof PersistentResource) {
                $stream = $attachment->getStream();
                if (!is_resource($stream)) {
                    continue;
                }

                $content = stream_get_contents($stream);
                if ($content === false) {
                    continue;
                }

                $mail->addPart(new DataPart($content, $attachment->getFilename(), $attachment->getMediaType()));
                continue;
            }

            if (!is_array($attachment) || !isset($attachment['content'], $attachment['name'])) {
                continue;
            }

            $mail->addPart(new DataPart(
                $attachment['content'],
                (string)$attachment['name'],
                isset($attachment['type']) ? (string)$attachment['type'] : MediaTypes::getMediaTypeFromFilename((string)$attachment['name'])
            ));
        }
    }

    private function requireStringOption(string $name): string
    {
        $value = $this->options[$name] ?? null;
        if (!is_string($value) || $value === '') {
            throw new \RuntimeException(sprintf('The option "%s" must be set for the EmailAction.', $name), 1327060320);
        }

        return $value;
    }
}
