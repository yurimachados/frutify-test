<?php

namespace App\UseCases\Contact;

use App\DTOs\UpdateContactDto;
use App\Models\Contact;
use App\Repositories\Contracts\ContactRepositoryInterface;

/**
 * Business logic for updating existing contacts.
 * 
 * Handles email uniqueness validation and contact data updates
 * while maintaining business rules and data integrity.
 */
class UpdateContactUseCase
{
    /**
     * Create a new update contact use case instance.
     *
     * @param ContactRepositoryInterface $contactRepository
     */
    public function __construct(
        private ContactRepositoryInterface $contactRepository
    ) {}

    /**
     * Execute contact update operation.
     *
     * Validates email uniqueness (excluding current contact) and
     * updates contact data with normalized phone number.
     *
     * @param UpdateContactDto $contactData
     * @return Contact Updated contact instance
     * @throws \InvalidArgumentException When email already exists for another contact
     */
    public function execute(UpdateContactDto $contactData): Contact
    {
        $this->validateEmailUniqueness($contactData);

        return $this->contactRepository->update($contactData->id, [
            'name' => $contactData->name,
            'email' => $contactData->email,
            'phone' => $this->normalizePhoneNumber($contactData->phone),
        ]);
    }

    /**
     * Validate that email is unique excluding the current contact.
     *
     * @param UpdateContactDto $contactData
     * @throws \InvalidArgumentException When email already exists
     */
    private function validateEmailUniqueness(UpdateContactDto $contactData): void
    {
        $existingContactWithEmail = $this->contactRepository->findByEmail($contactData->email);
        
        if ($existingContactWithEmail && $existingContactWithEmail->id !== $contactData->id) {
            throw new \InvalidArgumentException('Email already exists');
        }
    }

    /**
     * Remove non-digit characters from phone number.
     *
     * @param string $phone
     * @return string Normalized phone number
     */
    private function normalizePhoneNumber(string $phone): string
    {
        return preg_replace('/\D/', '', $phone);
    }
}
