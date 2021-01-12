<?php

namespace ApiMock\Core;

use ApiMock\Interfaces\ResponseInterface;
use ApiMock\Response\ManualResponse;
use ApiMock\Response\SuccessResponse;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class ApiMock
{
    public const BASE_URI = 'http://docker.local:7331';

    /** @var Client */
    private $client;
    /** @var string */
    private $sessionId;
    /** @var ResponseInterface */
    private $defaultResponse;
    /** @var string|null */
    private $sessionCacheFile;

    /**
     * ApiMock constructor.
     *
     * @param Client $client
     * @param null|string $sessionId
     * @param string|null $sessionCacheFile
     */
    public function __construct(Client $client, ?string $sessionId = null, ?string $sessionCacheFile = null)
    {
        $this->sessionCacheFile = $sessionCacheFile;

        $this->setClient($client);
        $this->setSessionID($sessionId ?? '');
        $this->setDefaultResponse(new SuccessResponse());
    }

    /**
     * Get the client
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Sets the client
     *
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the session id
     * @return string
     */
    public function getSessionID(): string
    {
        return $this->sessionId;
    }

    /**
     * Sets the session id
     *
     * @param string $sessionId
     */
    public function setSessionID(string $sessionId)
    {
        $this->sessionId = $sessionId;

        if ($this->getSessionCacheFile() !== null) {
            file_put_contents($this->getSessionCacheFile(), $sessionId);
        }
    }

    /**
     * Adds the given response to api mock
     *
     * @param ResponseInterface $response
     * @param int $order
     * @param string|null $requestKey
     *
     * @return bool
     */
    public function addResponse(ResponseInterface $response, int $order = 0, string $requestKey = null): bool
    {
        $res = $this->getClient()->post(
            '/' . ((strlen($this->getSessionID()) >= 1) ? '?session_id=' . $this->getSessionID() : ''),
            [
                RequestOptions::JSON => [
                    'status_code' => $response->getStatusCode(),
                    'data' => $response->getBody(),
                    'headers' => $response->getHeaders(),
                    'order' => $order,
                    'request_key' => $requestKey,
                ]
            ]
        );
        $this->setSessionID((string)$res->getBody());
        return $res->getStatusCode() === 201;
    }

    /**
     * Get the count of not retrieved mocks
     * @return int
     */
    public function count(): int
    {
        return (int)(string)$this->getClient()->get('/api-mock/count?session_id=' . $this->getSessionID())->getBody();
    }

    /**
     * Remove all not retrieved mocks
     */
    public function clear()
    {
        $this->getClient()->get('/api-mock/clear-session?session_id=' . $this->getSessionID());
    }

    /**
     * Receives a response from api mock
     *
     * @param string|null $sessionId
     *
     * @return ResponseInterface
     */
    public function getResponse($sessionId = null): ResponseInterface
    {
        if (is_null($sessionId)) {
            $sessionId = $this->getSessionID();
        }
        if ($this->count() <= 0) {
            return $this->defaultResponse;
        }
        $response = $this->getClient()->get('/halloWelt?session_id=' . $sessionId);
        return new ManualResponse($response->getStatusCode(), $response->getHeaders(), (string)$response->getBody());
    }

    /**
     * Sets the default response
     *
     * @param ResponseInterface $response
     */
    public function setDefaultResponse(ResponseInterface $response)
    {
        $this->defaultResponse = $response;
    }

    /**
     * Receive the default response object
     * @return ResponseInterface
     */
    public function getDefaultResponse(): ResponseInterface
    {
        return $this->defaultResponse;
    }

    /**
     * @param string $requestKey
     * @param null $sessionId
     *
     * @return array|null
     */
    public function getClientRequest(string $requestKey, $sessionId = null)
    {
        if (is_null($sessionId)) {
            $sessionId = $this->getSessionID();
        }
        $response = $this->getClient()->get('/api-mock/client-request?session_id=' . $sessionId . '&request_key=' . $requestKey);
        if (!empty($response)) {
            return json_decode($response->getBody()->getContents(), true);
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getSessionCacheFile(): ?string
    {
        return $this->sessionCacheFile;
    }
}
