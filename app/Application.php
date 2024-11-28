<?php

namespace Mike\Bnovo;

use Dotenv\Dotenv;
use Mike\Bnovo\Http\JsonResponse;
use Mike\Bnovo\Services\Database;
use Mike\Bnovo\Services\Routing;

class Application
{
    public static function run(): JsonResponse
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        try {
            if (env('APP_ENV') === 'prod') {
                ini_set('display_errors', 0);
                error_reporting(E_ALL & ~E_WARNING);
            }

            Database::init();
            return Routing::call();
        } catch (\Throwable $e) {
            if (env('APP_ENV') === 'prod') {
                return new JsonResponse('Internal server error', 500);
            }

            return new JsonResponse($e->getMessage(), 500);
        }
    }
}