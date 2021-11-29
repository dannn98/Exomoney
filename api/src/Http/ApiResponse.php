<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    /**
     * ApiResponse constructor
     *
     * @param string $message
     * @param mixed $data
     * @param array $errors
     * @param int $status
     * @param array $headers
     * @param bool $json
     */
    public function __construct(string $message, mixed $data = null, array $errors = [], int $status = 200, array $headers = [], bool $json = false)
    {
        parent::__construct($this->format($message, $data, $errors), $status, $headers, $json);
    }

    /**
     * Function for packing message, data and errors into array
     *
     * @param string $message
     * @param mixed $data
     * @param array $errors
     *
     * @return array
     */
    private function format(string $message, mixed $data = null, array $errors = []): array
    {
        if ($data === null) {
            $data = new \ArrayObject();
        }

        //TODO: Refaktor
//        $jsonDecode = json_decode($data);
//        if(json_last_error() === JSON_ERROR_NONE) {
//            $data = $jsonDecode;
//        }

        $response = [
            'message' => $message,
            'data' => $data
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return $response;
    }
}