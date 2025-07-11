<?php

namespace App\Http\DTOs;

use JsonSerializable;
use Illuminate\Contracts\Support\Arrayable;

abstract class BaseDTO implements JsonSerializable, Arrayable
{
    /**
     * Constructor to initialize DTO properties from array data
     */
    public function __construct(array $data = [])
    {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            
            if (array_key_exists($propertyName, $data)) {
                $this->$propertyName = $data[$propertyName];
            } elseif ($property->hasType() && !$property->getType()->allowsNull() && $property->isReadOnly()) {
                // For required readonly properties, we need to set a default value
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
     * Create a new DTO instance from array data
     */
    public static function fromArray(array $data): static
    {
        return new static($data);
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