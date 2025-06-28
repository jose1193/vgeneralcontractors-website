<?php

namespace App\Http\DTOs;

class InsuranceCompanyDTO extends BaseDTO
{
    public ?string $uuid = null;
    public string $insurance_company_name;
    public string $address;
    public string $phone;
    public string $email;
    public ?string $website = null;
    public ?int $user_id = null;

    // Puedes agregar métodos de validación, transformación, etc. si lo necesitas
} 