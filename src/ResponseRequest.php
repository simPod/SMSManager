<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

class ResponseRequest
{
    /** @var int */
    protected $requestId;

    /** @var int */
    protected $customId;

    /** @var string[] */
    protected $numbers = [];

    /** @var int */
    protected $smsCount;

    /** @var float */
    protected $smsPrice;

    public function __construct(int $requestId, int $customId, int $smsCount, float $smsPrice)
    {
        $this->requestId = $requestId;
        $this->customId  = $customId;
        $this->smsCount  = $smsCount;
        $this->smsPrice  = $smsPrice;
    }

    public function getRequestId() : int
    {
        return $this->requestId;
    }

    public function getCustomId() : int
    {
        return $this->customId;
    }

    public function addNumber(string $number) : void
    {
        $this->numbers[] = $number;
    }

    /**
     * @return string[]
     */
    public function getNumbers() : array
    {
        return $this->numbers;
    }

    public function getSmsCount() : int
    {
        return $this->smsCount;
    }

    public function getSmsPrice() : float
    {
        return $this->smsPrice;
    }
}
