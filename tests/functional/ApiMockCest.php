<?php

namespace ApiMock\Tests\Functional;

use ApiMock\Interfaces\ResponseInterface;
use ApiMock\Response\ManualResponse;
use ApiMock\Response\NoContentResponse;
use FunctionalTester;

class ApiMockCest
{
    /**
     * TEST: Try to add a mock response
     * @param FunctionalTester $I
     */
    public function tryToAddResponse(FunctionalTester $I)
    {
        $I->addResponse(new NoContentResponse());
    }

    /**
     * TEST: Try to see that the mock response added
     * @param FunctionalTester $I
     */
    public function tryToSeeCountIsOne(FunctionalTester $I)
    {
        $I->seeCount(1);
    }

    /**
     * TEST: Try to receive the added response
     * @param FunctionalTester $I
     */
    public function tryToSeeAddedResponse(FunctionalTester $I)
    {
        $I->addResponse(new NoContentResponse());
        $I->getResponse();
    }

    /**
     * TEST: Try to save default response
     * @param FunctionalTester $I
     */
    public function tryToSaveDefaultResponse(FunctionalTester $I)
    {
        $response = new ManualResponse(314, [], ['hallo' => 'surplex']);
        $I->setDefaultResponse($response);
        $this->tryToSeeDefaultResponseWhenNoOtherResponseIsAvailable($I, $response);
    }

    /**
     * TEST: Try to see default response when no other response is available
     * @param FunctionalTester $I
     * @param ResponseInterface $response
     */
    private function tryToSeeDefaultResponseWhenNoOtherResponseIsAvailable(FunctionalTester $I, ResponseInterface $response)
    {
        $I->clear();
        $I->validateResponseOnEquality($response, $I->getResponse());
    }
}
