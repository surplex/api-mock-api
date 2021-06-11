<?php

namespace ApiMock\Proxy;

use ApiMock\Core\ApiMock;
use GuzzleHttp\HandlerStack;
use function GuzzleHttp\choose_handler;

class ProxyStack extends HandlerStack
{
    public function __construct(ApiMock $apiMock, callable $handler = null)
    {
        parent::__construct($handler ?? choose_handler());
        $this->push(ProxyMiddleware::create($apiMock));
    }
}