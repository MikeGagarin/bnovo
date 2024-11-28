<?php

namespace Mike\Bnovo\Http;

class Request
{
    private ?array $get;
    private ?array $post;

    public function __construct(array $get = null, array $post = null) {
        $this->get = $get;
        $this->post = $post;
    }

    public function get(): ?array
    {
        return $this->get;
    }

    public function post(): ?array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->isJson()) {
                return $this->getJsonPayload();
            } else {
                return $this->post;
            }
        }

        return null;
    }

    private function isJson()
    {
        return isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }

    private function getJsonPayload()
    {
        $json = file_get_contents('php://input');
        return json_decode($json, true);
    }
}