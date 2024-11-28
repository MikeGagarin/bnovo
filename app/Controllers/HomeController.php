<?php

namespace Mike\Bnovo\Controllers;

use Mike\Bnovo\Http\JsonResponse;

class HomeController
{
    public function index(): JsonResponse
    {
        return new JsonResponse('Список доступных эндпоинтов', 200, [
            '/guest/list' => 'GET: Список всех гостей',
            '/guest/find' => 'GET: Находи гостя по id',
            '/guest/destroy' => 'DELETE: Удаляет гостя по id',
            '/guest/store' => 'POST: Список всех гостей',
        ]);
    }
}