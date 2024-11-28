<?php

namespace Mike\Bnovo\Services\ValidatorService;

use Mike\Bnovo\Services\ValidatorService\Validators\ValidatorInterface;

class ValidatorService
{
    public static function validate(array $fieldRules): array
    {
        $errors = [];

        foreach ($fieldRules as $field => $rules) {
            /** @var ValidatorInterface $rule */
            foreach ($rules as $rule) {
                if (!$rule->validate()) {
                    $errors[$field] = $rule->getError();
                }
            }
        }

        return $errors;
    }
}