<?php

namespace ApiMock\Interfaces;

interface ResponseInterface
{
    /**
     * Returns the HTTP status code for the response.
     *
     * Specified in RFC 7231 Section 6
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Returns the payload body for the response
     *
     * Specified in RFC 2616 Section 4.3 and RFC 7230 Section 3.3
     * @return mixed
     */
    public function getBody();

    /**
     * Returns the response headers
     * Please use following syntax: [<field name> => <field value>]
     *
     * Specified in RFC 7230 Section 3.2
     * @return array
     */
    public function getHeaders(): array;
}
