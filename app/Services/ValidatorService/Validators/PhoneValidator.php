<?php

namespace Mike\Bnovo\Services\ValidatorService\Validators;

class PhoneValidator implements ValidatorInterface
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function validate(): bool
    {
        if (!$this->value) {
            return true;
        }

        $phoneNumber = preg_replace('/[\s\-\(\)]/', '', $this->value);
        $pattern = '/^\+\d{1,3}\d{10}$/';

        if (preg_match($pattern, $phoneNumber)) {
            return true;
        }

        return false;
    }

    public function getError(): string
    {
        return 'Invalid phone number.';
    }
}