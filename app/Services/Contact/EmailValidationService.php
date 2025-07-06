<?php

namespace App\Services\Contact;

use App\Contracts\Services\Contact\EmailValidationServiceInterface;
use App\Contracts\Repositories\Contacts\ContactRepositoryInterface;

/**
 * Service for contact email validation.
 *
 * Centralizes email validation logic to ensure consistency
 * across different Use Cases.
 */
class EmailValidationService implements EmailValidationServiceInterface
{
    public function __construct(
        private ContactRepositoryInterface $contactRepository
    ) {}

    /**
     * Check if email already exists in the system.
     *
     * @param string $email
     * @return bool
     */
    public function exists(string $email): bool
    {
        return $this->contactRepository->emailExists($email);
    }

    /**
     * Check if email is unique for a specific contact (excluding itself).
     *
     * @param string $email
     * @param int $excludeContactId
     * @return bool
     */
    public function isUniqueForContact(string $email, int $excludeContactId): bool
    {
        $existingContact = $this->contactRepository->findByEmail($email);

        return !$existingContact || $existingContact->id === $excludeContactId;
    }
}
