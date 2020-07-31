<?php

namespace Helper;

use ApiMock\Interfaces\ResponseInterface;
use ApiMock\Response\ManualResponse;
use Codeception\Module;

class ApiMock extends Module
{
    /** @var string SessionID of functional test */
    private static $sessionId;
    /** @var \ApiMock\Core\ApiMock */
    private $apiMock;
    /** @var string */
    private $baseUri = 'http://docker.local:7331';

    /**
     * {@inheritdoc}
     */
    public function _beforeSuite($settings = [])
    {
        self::$sessionId = bin2hex(random_bytes(32));
        if (isset($settings['modules']['enabled'][1]['\Helper\ApiMock']['baseUri'])) {
            $this->baseUri = $settings['modules']['enabled'][1]['\Helper\ApiMock']['baseUri'];
        }
        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->getUri()
        ]);
        $this->apiMock = new \ApiMock\Core\ApiMock($client, self::$sessionId);
    }

    /**
     * {@inheritdoc}
     */
    public function _afterSuite()
    {
        $this->apiMock->clear();
    }

    /**
     * Test if session id expect given session id
     * @param string $sessionId session id that I am expect
     */
    public function seeSessionId(string $sessionId)
    {
        $this->assertTrue($sessionId == $this->apiMock->getSessionID(), 'given session id not equals saved session id');
    }

    /**
     * Test if count equals expected count
     * @param int $count count that I am expect
     */
    public function seeCount(int $count)
    {
        $this->assertTrue($this->apiMock->count() == $count, 'given count not equals real count');
    }

    /**
     * Test if given response is added
     * @param ResponseInterface $response
     */
    public function addResponse(ResponseInterface $response)
    {
        $this->assertTrue($this->apiMock->addResponse($response), 'Given response could not be added');
    }

    /**
     * Returns an instance of ApiMock
     * @return \ApiMock\Core\ApiMock
     */
    public function getApiMock(): \ApiMock\Core\ApiMock
    {
        return $this->apiMock;
    }
    
    /**
     * Returns the base uri to api mock
     * @return string
     */
    public function getUri(): string
    {
        return $this->baseUri;
    }

    /**
     * Test that response is correctly formatted/instanced
     * @param null $sessionId
     * @return ResponseInterface
     */
    public function getResponse($sessionId = null): ResponseInterface
    {
        if (is_null($sessionId)) {
            $sessionId = $this->apiMock->getSessionID();
        }
        $response = $this->apiMock->getResponse($sessionId);
        $this->assertTrue(get_class($response) == ManualResponse::class);
        return $response;
    }

    /**
     * Test that given response is stored as default response
     * @param ResponseInterface $response
     */
    public function setDefaultResponse(ResponseInterface $response)
    {
        $this->apiMock->setDefaultResponse($response);
        $this->assertEquals($response, $this->apiMock->getDefaultResponse());
    }

    /**
     * Compares the status code and headers
     * @param ResponseInterface $a
     * @param ResponseInterface $b
     */
    public function validateResponseOnEquality(ResponseInterface $a, ResponseInterface $b)
    {
        $this->assertEquals($a->getStatusCode(), $b->getStatusCode(), 'The status code is not the same');
        $this->assertEquals($a->getBody(), $b->getBody(), 'The body is not the same');
    }

    /**
     * Removes all added responses
     */
    public function clear()
    {
        $this->apiMock->clear();
    }
}
