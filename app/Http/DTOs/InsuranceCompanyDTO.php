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

    public function __construct(
        ?string $uuid = null,
        string $insurance_company_name = '',
        ?string $address = null,
        ?string $phone = null,
        ?string $email = null,
        ?string $website = null,
        ?int $user_id = null,
        bool $is_active = true,
        ?string $created_at = null,
        ?string $updated_at = null,
        ?string $deleted_at = null
    ) {
        // If first parameter is an array, handle it as BaseDTO constructor
        if (is_array($uuid)) {
            $data = $uuid;
            $this->uuid = $data['uuid'] ?? null;
            $this->insurance_company_name = $data['insurance_company_name'] ?? '';
            $this->address = $data['address'] ?? null;
            $this->phone = $data['phone'] ?? null;
            $this->email = $data['email'] ?? null;
            $this->website = $data['website'] ?? null;
            $this->user_id = $data['user_id'] ?? null;
            $this->is_active = $data['is_active'] ?? ($data['deleted_at'] === null);
            $this->created_at = $data['created_at'] ?? null;
            $this->updated_at = $data['updated_at'] ?? null;
            $this->deleted_at = $data['deleted_at'] ?? null;
        } else {
            // Handle named parameters
            $this->uuid = $uuid;
            $this->insurance_company_name = $insurance_company_name;
            $this->address = $address;
            $this->phone = $phone;
            $this->email = $email;
            $this->website = $website;
            $this->user_id = $user_id;
            $this->is_active = $is_active;
            $this->created_at = $created_at;
            $this->updated_at = $updated_at;
            $this->deleted_at = $deleted_at;
        }
    }

    public static function fromModel($model): self
    {
        return new self(
            uuid: $model->uuid,
            insurance_company_name: $model->insurance_company_name,
            address: $model->address,
            phone: $model->phone,
            email: $model->email,
            website: $model->website,
            user_id: $model->user_id,
            is_active: $model->isActive(),
            created_at: $model->created_at?->toISOString(),
            updated_at: $model->updated_at?->toISOString(),
            deleted_at: $model->deleted_at?->toISOString()
        );
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