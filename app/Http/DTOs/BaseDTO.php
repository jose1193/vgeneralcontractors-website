<?php

namespace App\Http\DTOs;

use JsonSerializable;
use Illuminate\Contracts\Support\Arrayable;

abstract class BaseDTO implements JsonSerializable, Arrayable
{
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
            $array[$property->getName()] = $property->getValue($this);
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