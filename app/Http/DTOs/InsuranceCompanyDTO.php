<?php

namespace App\Http\DTOs;

/**
 * Insurance Company Data Transfer Object
 * 
 * PURE DATA CONTAINER - No validation logic (handled by FormRequest)
 * Just data formatting and transformation
 */
final class InsuranceCompanyDTO extends BaseDTO
{
    public readonly ?string $uuid;
    public readonly string $insurance_company_name;
    public readonly ?string $phone;
    public readonly ?string $email;
    public readonly ?string $website;
    public readonly ?string $address;
    public readonly int $user_id;
    public readonly bool $is_active;
    public readonly ?\DateTime $created_at;
    public readonly ?\DateTime $updated_at;

    /**
     * Constructor - Initialize with validated data only
     */
    public function __construct(array $data = [])
    {
        // Set defaults for required fields
        $data['insurance_company_name'] = $data['insurance_company_name'] ?? '';
        $data['user_id'] = $data['user_id'] ?? 0;
        $data['is_active'] = $data['is_active'] ?? true;
        
        parent::__construct($data);
    }

    /**
     * Transform data after filling - format and clean data
     */
    protected function transformData(): void
    {
        // Format phone number if provided
        if ($this->phone) {
            $this->phone = $this->formatPhone($this->phone);
        }

        // Format email if provided
        if ($this->email) {
            $this->email = $this->formatEmail($this->email);
        }

        // Format website if provided
        if ($this->website) {
            $this->website = $this->formatWebsite($this->website);
        }

        // Format company name
        if ($this->insurance_company_name) {
            $this->insurance_company_name = $this->formatCompanyName($this->insurance_company_name);
        }

        // Parse dates if provided as strings
        if (isset($this->created_at) && is_string($this->created_at)) {
            $this->created_at = new \DateTime($this->created_at);
        }

        if (isset($this->updated_at) && is_string($this->updated_at)) {
            $this->updated_at = new \DateTime($this->updated_at);
        }
    }

    /**
     * Format phone number to (XXX) XXX-XXXX
     */
    private function formatPhone(string $phone): string
    {
        // Already formatted? Return as-is
        if (preg_match('/^\(\d{3}\)\s\d{3}-\d{4}$/', $phone)) {
            return $phone;
        }

        // Clean and format
        $cleaned = preg_replace('/\D/', '', $phone);
        
        if (strlen($cleaned) === 10) {
            return sprintf('(%s) %s-%s', 
                substr($cleaned, 0, 3),
                substr($cleaned, 3, 3),
                substr($cleaned, 6, 4)
            );
        }
        
        if (strlen($cleaned) === 11 && str_starts_with($cleaned, '1')) {
            $cleaned = substr($cleaned, 1);
            return sprintf('(%s) %s-%s', 
                substr($cleaned, 0, 3),
                substr($cleaned, 3, 3),
                substr($cleaned, 6, 4)
            );
        }

        return $phone; // Return original if can't format
    }

    /**
     * Format email to lowercase
     */
    private function formatEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    /**
     * Format website URL
     */
    private function formatWebsite(string $website): string
    {
        $website = trim($website);
        
        if (!str_starts_with($website, 'http://') && !str_starts_with($website, 'https://')) {
            $website = 'https://' . $website;
        }
        
        return $website;
    }

    /**
     * Format company name
     */
    private function formatCompanyName(string $name): string
    {
        return trim($name);
    }

    /**
     * Get data ready for database insertion/update
     */
    public function toDatabase(): array
    {
        $data = $this->toArray();
        
        // Remove UUID for creation
        if (empty($data['uuid'])) {
            unset($data['uuid']);
        }

        // Convert DateTime objects to strings for database
        if ($data['created_at'] instanceof \DateTime) {
            $data['created_at'] = $data['created_at']->format('Y-m-d H:i:s');
        }

        if ($data['updated_at'] instanceof \DateTime) {
            $data['updated_at'] = $data['updated_at']->format('Y-m-d H:i:s');
        }

        return $data;
    }

    /**
     * Get display-friendly data
     */
    public function toDisplay(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->insurance_company_name,
            'phone' => $this->phone ?: __('common.not_provided'),
            'email' => $this->email ?: __('common.not_provided'),
            'website' => $this->website ?: __('common.not_provided'),
            'address' => $this->address ?: __('common.not_provided'),
            'status' => $this->is_active ? __('common.active') : __('common.inactive'),
            'created_at' => $this->created_at?->format('M d, Y'),
            'updated_at' => $this->updated_at?->format('M d, Y'),
        ];
    }

    /**
     * Check if this is a new record (no UUID)
     */
    public function isNew(): bool
    {
        return empty($this->uuid);
    }

    /**
     * Get the identifier for this record
     */
    public function getIdentifier(): ?string
    {
        return $this->uuid;
    }
}
