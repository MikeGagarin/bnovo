<?php

namespace Mike\Bnovo\Controllers;

use Mike\Bnovo\Http\JsonResponse;
use Mike\Bnovo\Http\Request;
use Mike\Bnovo\Models\Guest;
use Mike\Bnovo\Services\ValidatorService\Validators\EmailValidator;
use Mike\Bnovo\Services\ValidatorService\Validators\PhoneValidator;
use Mike\Bnovo\Services\ValidatorService\Validators\RequiredValidator;
use Mike\Bnovo\Services\ValidatorService\Validators\UniqueValidator;
use Mike\Bnovo\Services\ValidatorService\ValidatorService;

class GuestController
{
    public function index(): JsonResponse
    {
        $models = [];

        foreach ((Guest::all() ?? []) as $guest) {
            $models[] = $guest->toArray();
        }

        return new JsonResponse(data: $models);
    }

    public function find(Request $request): JsonResponse
    {
        $payload = $request->get();

        if (!$payload || !$payload['id']) {
            return new JsonResponse('Parameter id is required', 404);
        }

        $guest = Guest::find($payload['id']);

        if (!$guest) {
            return new JsonResponse('Guest not found', 404);
        }

        return new JsonResponse(data: $guest->toArray());
    }

    public function store(Request $request): JsonResponse
    {
        $payload = $request->post();

        if (!$payload) {
            return new JsonResponse('Nothing to save', 400);
        }

        if ($payload['id']) {
            $guest = Guest::find($payload['id']);

            if (!$guest) {
                return new JsonResponse('Guest not found', 404);
            }
        }

        $validatorErrors = ValidatorService::validate([
            'name' => [new RequiredValidator($payload['name'])],
            'last_name' => [new RequiredValidator($payload['last_name'])],
            'phone' => [
                new RequiredValidator($payload['phone']),
                new PhoneValidator($payload['phone']),
                new UniqueValidator(Guest::tableName(), 'phone', $payload['phone'])
            ],
            'email' => [
                new EmailValidator($payload['email']),
                new UniqueValidator(Guest::tableName(), 'email', $payload['email'])
            ],
        ]);

        if (!empty($validatorErrors)) {
            return new JsonResponse('Form invalid', 400, $validatorErrors);
        }

        $guest = Guest::fromArray($payload);

        if ($guest->save()) {
            return new JsonResponse();
        }

        return new JsonResponse('Could not save guest', 500);
    }

    public function destroy(Request $request): JsonResponse
    {
        $payload = $request->get();

        if (!$payload || !$payload['id']) {
            return new JsonResponse('Parameter id is required', 404);
        }

        $guest = Guest::find($payload['id']);

        if (!$guest) {
            return new JsonResponse('Guest not found', 404);
        }

        if ($guest->delete()) {
            return new JsonResponse('Guest deleted successfully');
        }

        return new JsonResponse('Failed to delete guest', 500);
    }
}