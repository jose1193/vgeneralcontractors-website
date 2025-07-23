<?php

namespace App\Http\DTOs;

use JsonSerializable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class BaseDTO implements JsonSerializable, Arrayable
{
    /**
     * Constructor to initialize DTO properties from array data
     */
    public function __construct(array $data = [])
    {
        $this->fillFromArray($data);
        $this->validateData();
        $this->transformData();
    }

    /**
     * Fill DTO properties from array data
     */
    protected function fillFromArray(array $data): void
    {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            
            if (array_key_exists($propertyName, $data)) {
                $this->$propertyName = $this->castValue($data[$propertyName], $property);
            } elseif ($property->hasType() && !$property->getType()->allowsNull()) {
                // Set default values for non-nullable properties
                $type = $property->getType();
                if ($type instanceof \ReflectionNamedType) {
                    $this->$propertyName = match($type->getName()) {
                        'string' => '',
                        'int' => 0,
                        'float' => 0.0,
                        'bool' => false,
                        'array' => [],
                        default => null
                    };
                }
            }
        }
    }

    /**
     * Cast value based on property type
     */
    protected function castValue($value, \ReflectionProperty $property)
    {
        if (!$property->hasType()) {
            return $value;
        }

        $type = $property->getType();
        if (!$type instanceof \ReflectionNamedType) {
            return $value;
        }

        return match($type->getName()) {
            'string' => (string) $value,
            'int' => (int) $value,
            'float' => (float) $value,
            'bool' => (bool) $value,
            'array' => is_array($value) ? $value : [$value],
            default => $value
        };
    }

    /**
     * Validate data - to be overridden by child classes
     */
    protected function validateData(): void
    {
        // Default implementation does nothing
        // Child classes can override to add validation
    }

    /**
     * Transform data after filling - to be overridden by child classes
     */
    protected function transformData(): void
    {
        // Default implementation does nothing
        // Child classes can override to add transformations
    }

    /**
     * Create a new DTO instance from array data
     */
    public static function fromArray(array $data): static
    {
        return new static($data);
    }

    /**
     * Create DTO instance from model
     */
    public static function fromModel($model): static
    {
        return new static($model->toArray());
    }

    /**
     * Create collection of DTOs from array of data
     */
    public static function fromCollection($collection): Collection
    {
        return collect($collection)->map(function ($item) {
            return static::fromArray(is_array($item) ? $item : $item->toArray());
        });
    }

    /**
     * Create a new DTO instance from request data
     */
    public static function fromRequest($request): static
    {
        $data = is_array($request) ? $request : $request->all();
        return static::fromArray($data);
    }

    /**
     * Convert DTO to array
     */
    public function toArray(): array
    {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        
        $array = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            
            // Check if property is initialized before accessing
            if ($property->isInitialized($this)) {
                $array[$propertyName] = $property->getValue($this);
            } else {
                $array[$propertyName] = null;
            }
        }
        
        return $array;
    }

    /**
     * Convert DTO to JSON
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Get only non-null values as array
     */
    public function toArrayFiltered(): array
    {
        return array_filter($this->toArray(), fn($value) => !is_null($value));
    }

    /**
     * Check if DTO has any data
     */
    public function isEmpty(): bool
    {
        return empty(array_filter($this->toArray()));
    }

    /**
     * Get specific fields as array
     */
    public function only(array $fields): array
    {
        return array_intersect_key($this->toArray(), array_flip($fields));
    }

    /**
     * Exclude specific fields from array
     */
    public function except(array $fields): array
    {
        return array_diff_key($this->toArray(), array_flip($fields));
    }

    /**
     * Merge with another array or DTO
     */
    public function merge($data): array
    {
        $mergeData = $data instanceof self ? $data->toArray() : $data;
        return array_merge($this->toArray(), $mergeData);
    }
}