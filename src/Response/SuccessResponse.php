<?php

namespace ApiMock\Response;

use ApiMock\Helper\JsonHelper;
use ApiMock\Interfaces\ResponseInterface;

class SuccessResponse implements ResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return JsonHelper::toJSON([
            'success' => true
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return 200;
    }
}
