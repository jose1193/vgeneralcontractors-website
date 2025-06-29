<?php

namespace App\Http\DTOs;

class InsuranceCompanyDTO extends BaseDTO
{
    public ?string $uuid = null;
    public string $insurance_company_name;
    public ?string $address = null;
    public ?string $phone = null;
    public ?string $email = null;
    public ?string $website = null;
    public ?int $user_id = null;

    // Puedes agregar métodos de validación, transformación, etc. si lo necesitas
}