<?php

namespace Mike\Bnovo\Services\ValidatorService\Validators;

use Mike\Bnovo\Services\Database;

class UniqueValidator implements ValidatorInterface
{
    private string $tableName;
    private string $property;
    private $value;

    public function __construct(string $tableName, string $property, $value)
    {
        $this->tableName = $tableName;
        $this->value = $value;
        $this->property = $property;
    }

    public function validate(): bool
    {
        if (!$this->value) {
            return true;
        }

        $stmt = Database::instance()->query("SELECT $this->property FROM $this->tableName WHERE $this->property = '$this->value'");
        return $stmt->rowCount() === 0;
    }

    public function getError(): string
    {
        return 'Value not unique';
    }
}