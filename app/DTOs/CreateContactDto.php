<?php

namespace App\DTOs;

/**
 * Data Transfer Object for contact creation.
 */
class CreateContactDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $phone
    ) {}

    /**
     * Create DTO from array data.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone']
        );
    }

    /**
     * Convert DTO to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone
        ];
    }
}
