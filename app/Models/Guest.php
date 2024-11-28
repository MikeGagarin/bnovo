<?php

namespace Mike\Bnovo\Models;

use Mike\Bnovo\Abstracts\ActiveRecord;
use Mike\Bnovo\Services\CountryService;

class Guest extends ActiveRecord
{
    public string $name;
    public string $lastName;
    public ?string $email = null;
    public string $phone;
    public ?string $country = null;

    public function __construct(string $name, string $lastName, string $phone, int $id = null, string $email = null, int $country = null)
    {
        $this->name = $name;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->email = $email;
        $this->country = $country ?? CountryService::getFromCode(mb_substr($this->phone, 0, 2, 'UTF-8'));
        $this->id = $id;
    }
}