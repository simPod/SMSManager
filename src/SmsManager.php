<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

interface SmsManager
{
    /**
     * @return Response|bool
     */
    public function send(SmsMessage $smsMessage);
}
