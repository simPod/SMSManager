<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

use SimPod\SmsManager\Exception\InvalidPhoneNumber;
use function preg_match;

class Sms
{
    /** @var string */
    protected $message;

    /** @var RequestType */
    protected $type;

    /** @var string[] */
    protected $recipients = [];

    /** @var string|null */
    protected $sender;

    /** @var int|null */
    protected $customId;

    /**
     * @param string[] $recipients
     */
    public function __construct(
        string $message,
        array $recipients,
        ?RequestType $type = null,
        ?string $sender = null,
        ?int $customId = null
    ) {
        $this->message = $message;

        if ($type === null) {
            $type = RequestType::getRequestTypeHigh();
        }
        $this->type     = $type;
        $this->sender   = $sender;
        $this->customId = $customId;

        foreach ($recipients as $recipient) {
            if (! (preg_match('/^\+420(?:(?:60[1-8]|7(?:0[2-5]|[2379]\d))\d{6})$/', $recipient)
                || (
                    preg_match(
                        '/^\+4219(?:0(?:[1-8]\d|9[1-9])|(?:1[0-24-9]|4[04589]|50)\d)\d{5}$/',
                        $recipient
                    )
                    && $type !== RequestType::getRequestTypeLow()
                ))
            ) {
                throw InvalidPhoneNumber::notInSmsManagerFormat($recipient);
            }
        }

        $this->recipients = $recipients;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    public function getType() : RequestType
    {
        return $this->type;
    }

    /**
     * @return string[]
     */
    public function getRecipients() : array
    {
        return $this->recipients;
    }

    public function getSender() : ?string
    {
        return $this->sender;
    }

    public function getCustomId() : ?int
    {
        return $this->customId;
    }
}
