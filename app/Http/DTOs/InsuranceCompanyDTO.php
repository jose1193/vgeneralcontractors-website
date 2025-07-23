<?php

namespace App\Http\DTOs;

use Illuminate\Support\Str;

class InsuranceCompanyDTO extends BaseDTO
{
    public ?string $uuid;
    public string $insurance_company_name;
    public ?string $address;
    public ?string $phone;
    public ?string $email;
    public ?string $website;
    public ?int $user_id;
    public bool $is_active;
    public ?string $created_at;
    public ?string $updated_at;
    public ?string $deleted_at;

    public function __construct(array $data = [])
    {
        // Set defaults for required fields
        $data = array_merge([
            'uuid' => $data['uuid'] ?? (string) Str::uuid(),
            'insurance_company_name' => '',
            'address' => null,
            'phone' => null,
            'email' => null,
            'website' => null,
            'user_id' => $data['user_id'] ?? auth()->id(),
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
            'deleted_at' => null,
        ], $data);
        
        // Skip validation since data comes from validated FormRequest
        $this->fillFromArray($data);
        $this->transformData();
    }

    /**
     * Transform data after filling
     */
    protected function transformData(): void
    {
        // Clean and format insurance company name
        if (!empty($this->insurance_company_name)) {
            $this->insurance_company_name = trim($this->insurance_company_name);
        }

        // Clean and format email
        if (!empty($this->email)) {
            $this->email = strtolower(trim($this->email));
        }

        // Format website - same logic as FormRequest
        if (!empty($this->website)) {
            $website = trim($this->website);
            if (!preg_match('/^https?:\/\//', $website)) {
                $this->website = 'https://' . $website;
            } else {
                $this->website = $website;
            }
        }

        // Phone formatting should already be done by FormRequest
        // DTO just ensures consistency
        if (!empty($this->phone)) {
            $this->phone = trim($this->phone);
        }
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