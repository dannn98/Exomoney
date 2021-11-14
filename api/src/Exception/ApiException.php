<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ApiException extends \Exception implements HttpExceptionInterface
{
    private array $data;
    private int $statusCode;
    private array $headers;

    /**
     * ApiException constructor
     *
     * @param string $message
     * @param array $data
     * @param int $statusCode
     * @param array $headers
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", $data = [], int $statusCode = 400, array $headers = [], int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}