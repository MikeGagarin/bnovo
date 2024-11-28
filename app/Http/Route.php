<?php

namespace Mike\Bnovo\Http;
use Mike\Bnovo\Enums\HttpMethods;

class Route
{
    /** @var static[] $registeredRoutes */
    private static array $registeredRoutes = [];

    public HttpMethods $method;
    public string $path;
    public string $controller;
    public string $action;

    public function __construct(HttpMethods $method, string $path, string $controller, string $action)
    {
        $this->method = $method;
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;

        self::$registeredRoutes[] = $this;
    }

    public static function get(string $path, string $controller, string $action): static
    {
        return new static(HttpMethods::GET, $path, $controller, $action);
    }

    public static function post(string $path, string $controller, string $action): static
    {
        return new static(HttpMethods::POST, $path, $controller, $action);
    }

    public static function patch(string $path, string $controller, string $action): static
    {
        return new static(HttpMethods::PATCH, $path, $controller, $action);
    }

    public static function put(string $path, string $controller, string $action): static
    {
        return new static(HttpMethods::PATCH, $path, $controller, $action);
    }

    public static function delete(string $path, string $controller, string $action): static
    {
        return new static(HttpMethods::DELETE, $path, $controller, $action);
    }

    /**
     * @return array|static[]
     */
    public static function getRegisteredRoutes(): array
    {
        return self::$registeredRoutes;
    }
}