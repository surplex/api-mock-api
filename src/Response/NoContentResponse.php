<?php

namespace ApiMock\Response;

use ApiMock\Interfaces\ResponseInterface;

class NoContentResponse implements ResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function getHeaders(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return 204;
    }
}
