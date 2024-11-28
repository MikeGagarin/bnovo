<?php

namespace Mike\Bnovo\Services\ValidatorService\Validators;

class RequiredValidator implements ValidatorInterface
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function validate(): bool
    {
        return !empty($this->value);
    }

    public function getError(): string
    {
        return "Value is required";
    }
}