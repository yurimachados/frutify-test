<?php

namespace App\UseCases\Contact;

use App\DTOs\CreateContactDto;
use App\Models\Contact;
use App\Repositories\Contracts\ContactRepositoryInterface;

/**
 * Use case for creating a new contact.
 */
class CreateContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $contactRepository
    ) {}

    /**
     * Execute the create contact use case.
     *
     * @param CreateContactDto $dto
     * @return Contact
     * @throws \InvalidArgumentException
     */
    public function execute(CreateContactDto $dto): Contact
    {
        // Business rule: Normalize phone number
        $normalizedPhone = $this->normalizePhone($dto->phone);

        // Business rule: Check if email already exists
        if ($this->contactRepository->emailExists($dto->email)) {
            throw new \InvalidArgumentException('Email already exists');
        }

        // Create contact data with normalized phone
        $contactData = [
            'name' => $dto->name,
            'email' => $dto->email,
            'phone' => $normalizedPhone
        ];

        // Create and return the contact
        return $this->contactRepository->create($contactData);
    }

    /**
     * Normalize phone number by removing non-numeric characters.
     *
     * @param string $phone
     * @return string
     */
    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\D/', '', $phone);
    }
}
