<?php

declare(strict_types=1);

namespace SimPod\SmsManager\Exception;

use Exception;
use SimpleXMLElement;
use function implode;
use function sprintf;

final class SendingFailed extends Exception
{
    /**
     * @param string[] $recipients
     */
    public static function forRecipients(array $recipients, SimpleXMLElement $response) : self
    {
        return new self(
            sprintf(
                'Sending failed for "%s" because "%s"',
                implode(', ', $recipients),
                (string) $response->Response[0]
            )
        );
    }
}
