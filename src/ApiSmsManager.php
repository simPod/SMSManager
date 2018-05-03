<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use SimPod\SmsManager\Exception\SendingFailed;
use function dom_import_simplexml;

final class ApiSmsManager implements SmsManager
{
    private const XML_BASE_PATH = 'http://xml-api.smsmanager.cz/';
    private const XML_PATH_SEND = 'Send';

    /** @var string */
    private $username;

    /**@var string */
    private $password;

    /**@var Client */
    private $xmlClient;

    public function __construct()
    {
        $this->xmlClient = new Client(
            [
                'base_uri' => self::XML_BASE_PATH,
            ]
        );
    }

    public function setAuth(string $username, string $password) : void
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return Response|bool
     */
    public function sendSms(Sms $sms)
    {
        $xml = $this->buildXml($sms);

        if ($xml === null) {
            return false;
        }

        try {
            $response = $this->xmlClient->post(
                self::XML_PATH_SEND,
                [
                    'multipart' => [
                        [
                            'name'     => 'XMLDATA',
                            'contents' => $xml,
                        ],
                    ],
                ]
            );

            return $this->buildResponseData($response);
        } catch (ClientException | ServerException $exception) {
            $response = Parser::parseXmlResponseBody($exception->getResponse());

            throw SendingFailed::forRecipients($sms->getRecipients(), $response);
        }
    }

    private function buildXml(Sms $sms) : ?string
    {
        $xml           = new \SimpleXMLElement('<RequestDocument/>');
        $requestHeader = $xml->addChild('RequestHeader');
        $requestHeader->addChild('Username', $this->username);
        $requestHeader->addChild('Password', $this->password);
        $request = $xml
            ->addChild('RequestList')
            ->addChild('Request');

        $request->addAttribute('Type', $sms->getType()->getValue());

        if ($sms->getSender() !== null) {
            $request->addAttribute('Sender', $sms->getSender());
        }

        if ($sms->getCustomId() !== null) {
            $request->addAttribute('CustomID', (string) $sms->getCustomId());
        }

        $request->addChild('Message', $sms->getMessage())->addAttribute('Type', 'Text');

        $numberList = $request->addChild('NumbersList');

        $hasAnyNumber = false;
        foreach ($sms->getRecipients() as $recipient) {
            Validator::isE164($recipient);
            $numberList->addChild('Number', $recipient);
            $hasAnyNumber = true;
        }

        /* removes <?xml version="1.0"?> */
        $dom = dom_import_simplexml($xml);
        $xml = $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);

        return $hasAnyNumber ? $xml : null;
    }

    private function buildResponseData(ResponseInterface $apiResponse) : Response
    {
        $result = new \SimpleXMLElement((string) $apiResponse->getBody());

        $responseId   = (int) $result->Response['ID'];
        $responseType = (string) $result->Response['Type'];

        $response = new Response($responseId, $responseType);

        /** @var \SimpleXMLElement $responseRequestList */
        $responseRequestList = $result->ResponseRequestList;

        foreach ($responseRequestList->ResponseRequest as $request) {
            $responseRequest = new ResponseRequest(
                (int) $request->RequestID,
                (int) $request->CustomID,
                (int) $request['SmsCount'],
                (float) $request['SmsPrice']
            );

            /** @var \SimpleXMLElement $responseNumbersList */
            $responseNumbersList = $request->ResponseNumbersList;
            foreach ($responseNumbersList->Number as $phoneNumber) {
                $responseRequest->addNumber((string) $phoneNumber);
            }

            $response->addResponseRequest($responseRequest);
        }

        return $response;
    }
}
