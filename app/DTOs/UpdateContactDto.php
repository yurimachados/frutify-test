<?php

namespace App\DTOs;

/**
 * Data Transfer Object for contact update operations.
 *
 * Encapsulates contact data required for update operations,
 * ensuring type safety and immutability.
 */
class UpdateContactDto
{
    /**
     * Create a new update contact DTO instance.
     *
     * @param int $id Contact identifier
     * @param string $name Contact full name
     * @param string $email Contact email address
     * @param string $phone Contact phone number
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $phone,
    ) {}

    /**
     * Create DTO instance from contact ID and validated request data.
     *
     * @param int $id Contact identifier
     * @param array $validatedData Validated form data
     * @return self
     */
    public static function fromArray(int $id, array $validatedData): self
    {
        return new self(
            id: $id,
            name: $validatedData['name'],
            email: $validatedData['email'],
            phone: $validatedData['phone'],
        );
    }

    /**
     * Convert DTO to array representation.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
