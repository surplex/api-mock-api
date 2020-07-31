<?php

namespace ApiMock\Tests\Unit;

use ApiMock\Core\ApiMock;
use GuzzleHttp\Psr7\Response;

class ApiMockTest extends \Codeception\Test\Unit
{
    /** @var \GuzzleHttp\Client | \PHPUnit\Framework\MockObject\MockObject */
    public $guzzleClientMock;
    /** @var Response */
    public $responseMock;
    /** @var ApiMock */
    public $apiMockObj;

    /**
     * {@inheritdoc}
     */
    public function _before()
    {
        $this->responseMock = new Response(201);
        $this->guzzleClientMock = $this->createMock(\GuzzleHttp\Client::class);
        $this->apiMockObj = new ApiMock($this->guzzleClientMock, 'nicht_meine_session_id');
    }

    /**
     * TEST: Client should get the same client object that he given it in the constructor
     */
    public function testIfClientIsSetItShouldBeReturnedByMethodGetClient()
    {
        $this->assertEquals($this->guzzleClientMock, $this->apiMockObj->getClient());
    }

    /**
     * TEST: Client should get the same session id that he given it in the method setSessionID
     */
    public function testIfSessionIdIsSettedItShouldEqualSettedSessionId()
    {
        $this->apiMockObj->setSessionID('meine_session_id');
        $this->assertEquals('meine_session_id', $this->apiMockObj->getSessionID());
    }

    /**
     * TEST: Client should not get the session id that he given it in the constructor.
     */
    public function testIfSessionIdIsOtherThanSettedSessionIdItShouldNotEqualSettedSessionId()
    {
        $this->apiMockObj->setSessionID('meine_session_id');
        $this->assertNotEquals('nicht_meine_session_id', $this->apiMockObj->getSessionID());
    }
}
