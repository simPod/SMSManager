<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

use SimPod\SmsManager\Exception\NoRecipientsProvided;
use function count;

class SmsMessage
{
    /** @var string */
    protected $message;

    /** @var RequestType */
    protected $requestType;

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
        ?RequestType $requestTypeHigh = null,
        ?string $sender = null,
        ?int $customId = null
    ) {
        if (count($recipients) === 0) {
            throw new NoRecipientsProvided();
        }

        $this->message = $message;

        if ($requestTypeHigh === null) {
            $requestTypeHigh = RequestType::getRequestTypeHigh();
        }
        $this->requestType = $requestTypeHigh;
        $this->sender      = $sender;
        $this->customId    = $customId;
        $this->recipients  = $recipients;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    public function getRequestType() : RequestType
    {
        return $this->requestType;
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
