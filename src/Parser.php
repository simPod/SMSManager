<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;
use SimPod\SmsManager\Exception\XmlParsingFailed;
use Throwable;
use function libxml_disable_entity_loader;
use function libxml_use_internal_errors;
use const LIBXML_NONET;

class Parser
{
    /**
     * @param mixed[] $config
     */
    public static function parseXmlResponseBody(?ResponseInterface $response, array $config = []) : SimpleXMLElement
    {
        $disableEntities = libxml_disable_entity_loader();
        $internalErrors  = libxml_use_internal_errors(true);
        try {
            // Allow XML to be retrieved even if there is no response body
            $xml = new SimpleXMLElement(
                $response === null ? '<root />' : (string) $response->getBody(),
                $config['libxml_options'] ?? LIBXML_NONET,
                false,
                $config['ns'] ?? '',
                $config['ns_is_prefix'] ?? false
            );
            libxml_disable_entity_loader($disableEntities);
            libxml_use_internal_errors($internalErrors);
        } catch (Throwable $exception) {
            libxml_disable_entity_loader($disableEntities);
            libxml_use_internal_errors($internalErrors);

            throw new XmlParsingFailed(
                'Unable to parse response body into XML: ' . $exception->getMessage(),
                0,
                $exception
            );
        }

        return $xml;
    }
}
