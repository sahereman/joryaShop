<?php

namespace App\Clients;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Http\GraphRawResponse;
use Facebook\HttpClients\FacebookHttpClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

class FacebookGuzzle6HttpClient implements FacebookHttpClientInterface
{
    /**
     * @var \GuzzleHttp\Client The Guzzle client.
     */
    private $guzzleClient;

    /**
     * @param \GuzzleHttp\Client|null The Guzzle client.
     */
    public function __construct(Client $guzzleClient = null)
    {
        $this->guzzleClient = $guzzleClient ?: new Client();
    }

    /**
     * @inheritdoc
     */
    public function send($url, $method, $body, array $headers, $timeOut)
    {
        $request = new Request($method, $url, $headers, $body);

        try {
            $response = $this->guzzleClient->send($request, ['timeout' => $timeOut]);
        } catch (RequestException $e) {
            throw new FacebookSDKException($e->getMessage(), $e->getCode());
        }

        $responseHeaders = $response->getHeaders();
        foreach ($responseHeaders as $key => $values) {
            $responseHeaders[$key] = implode(', ', $values);
        }

        $responseBody = $response->getBody()->getContents();
        $httpStatusCode = $response->getStatusCode();

        return new GraphRawResponse(
            $responseHeaders,
            $responseBody,
            $httpStatusCode
        );
    }
}
