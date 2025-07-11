<?php

namespace App\Http\DTOs;

class InsuranceCompanyDTO extends BaseDTO
{
    public readonly ?string $uuid;
    public readonly string $insurance_company_name;
    public readonly ?string $address;
    public readonly ?string $phone;
    public readonly ?string $email;
    public readonly ?string $website;
    public readonly ?int $user_id;
    public readonly bool $is_active;
    public readonly ?string $created_at;
    public readonly ?string $updated_at;
    public readonly ?string $deleted_at;

    public function __construct(array $data = [])
    {
        // Set defaults for required fields
        $data = array_merge([
            'uuid' => null,
            'insurance_company_name' => '',
            'address' => null,
            'phone' => null,
            'email' => null,
            'website' => null,
            'user_id' => auth()->id(), // Set authenticated user ID as default
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
            'deleted_at' => null,
        ], $data);
        
        parent::__construct($data);
    }

    /**
     * Create DTO from Eloquent model
     */
    public static function fromModel($model): static
    {
        return static::fromArray([
            'uuid' => $model->uuid,
            'insurance_company_name' => $model->insurance_company_name,
            'address' => $model->address,
            'phone' => $model->phone,
            'email' => $model->email,
            'website' => $model->website,
            'user_id' => $model->user_id,
            'is_active' => $model->is_active,
            'created_at' => $model->created_at?->toISOString(),
            'updated_at' => $model->updated_at?->toISOString(),
            'deleted_at' => $model->deleted_at?->toISOString(),
        ]);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function getStatusLabel(): string
    {
        return match($this->is_active) {
            true => 'Active',
            false => 'Inactive',
        };
    }

    public function getStatusColor(): string
    {
        return match($this->is_active) {
            true => 'green',
            false => 'red',
        };
    }

    public function getFormattedPhone(): ?string
    {
        return $this->phone;
    }

    public function getDisplayName(): string
    {
        return $this->insurance_company_name;
    }
}