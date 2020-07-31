<?php

namespace ApiMock\Response;

use ApiMock\Interfaces\ResponseInterface;

class ManualResponse implements ResponseInterface
{
    /** @var int */
    private $statusCode;
    /** @var array */
    private $headers;
    /** @var string */
    private $body;

    /**
     * ManualResponse constructor.
     * @param int $statusCode
     * @param array $headers
     * @param mixed $body
     */
    public function __construct(int $statusCode = 200, array $headers = [], $body = '')
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * Returns the HTTP status code for the response.
     *
     * Specified in RFC 7231 Section 6
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Returns the payload body for the response
     *
     * Specified in RFC 2616 Section 4.3 and RFC 7230 Section 3.3
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Returns the response headers
     * Please use following syntax: [<field name> => <field value>]
     *
     * Specified in RFC 7230 Section 3.2
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}