<?php

declare(strict_types=1);

namespace SimPod\SmsManager\Exception;

use LogicException;
use function sprintf;

final class InvalidPhoneNumber extends LogicException
{
    public static function notInE164Format(string $phoneNumber) : self
    {
        return new self(sprintf('Number "%s" is not valid according to E.164 recommendation', $phoneNumber));
    }
}
