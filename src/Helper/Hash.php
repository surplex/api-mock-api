<?php

namespace ApiMock\Helper;

use Psr\Http\Message\RequestInterface;

class Hash
{
    /**
     * Creates a hash based on the Request.
     * @param RequestInterface $request
     * @return string
     */
    public static function createHash(RequestInterface $request): string
    {
        return sha1($request->getUri()->getHost() . $request->getMethod());
    }
}