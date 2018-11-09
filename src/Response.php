<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

class Response
{
    private const STATUS_OK = 'OK';

    /** @var int */
    protected $id;

    /** @var string */
    protected $type;

    /** @var bool */
    protected $isOk;

    /** @var ResponseRequest[] */
    protected $responseRequests = [];

    public function __construct(int $id, string $type)
    {
        $this->id   = $id;
        $this->isOk = $type === self::STATUS_OK;
        $this->type = $type;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function addResponseRequest(ResponseRequest $responseRequest) : void
    {
        $this->responseRequests[] = $responseRequest;
    }

    /**
     * @return ResponseRequest[]
     */
    public function getResponseRequests() : array
    {
        return $this->responseRequests;
    }
}
