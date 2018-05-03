<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

use SimPod\SmsManager\Exception\InvalidPhoneNumber;
use function preg_match;

class Validator
{
    /**
     * @see https://en.wikipedia.org/wiki/E.164
     */
    public static function isE164(string $phoneNumber) : void
    {
        if (! preg_match('/\A\+(\d{1,3})([\d]{1,14})\z/', $phoneNumber)) {
            throw InvalidPhoneNumber::notInE164Format($phoneNumber);
        }
    }
}
