<?php

namespace Mike\Bnovo\Services;

use Mike\Bnovo\Enums\HttpMethods;
use Mike\Bnovo\Http\JsonResponse;
use Mike\Bnovo\Http\Request;
use Mike\Bnovo\Http\Route;

class Routing
{
    private static function match(HttpMethods $method, string $path): ?Route
    {
        foreach (Route::getRegisteredRoutes() as $route) {
            if ($route->method === $method && $route->path === $path) {
                return $route;
            }
        }

        return null;
    }

    public static function call(): JsonResponse
    {
        $method = HttpMethods::from($_SERVER['REQUEST_METHOD']);
        $parsedUrl = parse_url($_SERVER['REQUEST_URI']);
        $path = $parsedUrl['path'] ?? '/';

        $route = self::match($method, $path);

        if (!$route) {
            return new JsonResponse('Resource not found', 404);
        }

        $request = new Request($_GET, $_POST);
        $controllerInstance = new $route->controller();
        return $controllerInstance->{$route->action}($request);
    }
}