<?php

namespace Mike\Bnovo\Http;

class JsonResponse
{
    private string $message = 'success';
    private ?array $data = null;
    private int $statusCode = 200;

    public function __construct(string $message = 'success', int $statusCode = 200, array $data = null)
    {
        $this->message = $message;
        $this->data = $data;
        $this->statusCode = $statusCode;
    }

    public function send(): string
    {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');

        $response = [
            "message" => $this->message,
            "code" => $this->statusCode,
        ];

        if ($this->data) {
            $response['data'] = $this->data;
        }

        return json_encode($response);
    }
}