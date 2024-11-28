<?php

namespace Mike\Bnovo\Services\ValidatorService\Validators;

interface ValidatorInterface
{
    public function validate(): bool;

    public function getError(): string;
}