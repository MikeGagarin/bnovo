<?php

namespace Mike\Bnovo\Services\ValidatorService\Validators;

class EmailValidator implements ValidatorInterface
{
    private $email;

    public function __construct($email) {
        $this->email = $email;
    }

    public function validate(): bool
    {
        if (!$this->email) {
            return true;
        }

        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function getError(): string
    {
        return 'Email is not valid.';
    }
}