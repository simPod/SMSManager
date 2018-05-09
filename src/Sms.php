<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

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
        $this->type       = $type;
        $this->sender     = $sender;
        $this->customId   = $customId;
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
