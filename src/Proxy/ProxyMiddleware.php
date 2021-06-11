<?php

namespace ApiMock\Proxy;

use ApiMock\Core\ApiMock;
use ApiMock\Helper\Hash;
use ApiMock\Response\ManualResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ProxyMiddleware
{
    /** @var callable $handler */
    private $handler;
    private ApiMock $apiMock;

    /**
     * ProxyMiddleware constructor.
     * @param callable $handler
     * @param ApiMock $apiMock
     */
    public function __construct(callable $handler, ApiMock $apiMock)
    {
        $this->handler = $handler;
        $this->apiMock = $apiMock;
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @return mixed
     */
    public function __invoke(RequestInterface $request, array $options)
    {
        $promise = $this->handler;
        if ($this->existsResponse($request)) {
            /** @var \ApiMock\Interfaces\ResponseInterface $mockResponse */
            $mockResponse = unserialize(file_get_contents($this->getPath($request)));
            $this->apiMock->addResponse($mockResponse);
            $request = $request->withUri($request->getUri()->withHost($this->apiMock->getClient()->getConfig('base_uri')));
            return $promise($request, $options)->then(function (ResponseInterface $response) {
                return $response;
            });
        }
        return $promise($request, $options)->then(function (ResponseInterface $response) use ($request) {
            $this->saveResponse($response, $request);
            return $response;
        });
    }

    /**
     * @param ApiMock $apiMock
     * @return \Closure
     */
    public static function create(ApiMock $apiMock): \Closure
    {
        return function (callable $handler) use ($apiMock) {
            return new ProxyMiddleware($handler, $apiMock);
        };
    }

    /**
     * @param RequestInterface $request
     * @return string
     */
    private function getPath(RequestInterface $request): string
    {
        return sys_get_temp_dir() . DIRECTORY_SEPARATOR . Hash::createHash($request) . '.serialized';
    }

    /**
     * @param RequestInterface $request
     * @return bool
     */
    private function existsResponse(RequestInterface $request): bool
    {
        return file_exists($this->getPath($request));
    }

    /**
     * @param ResponseInterface $response
     * @param RequestInterface $request
     */
    private function saveResponse(ResponseInterface $response, RequestInterface $request)
    {
        $mockResponse = new ManualResponse($response->getStatusCode(), $response->getHeaders(), (string)$response->getBody());
        $serializedMockResponse = serialize($mockResponse);
        file_put_contents($this->getPath($request), $serializedMockResponse);
    }
}