<?php

namespace App\Contracts\Services\Contact;

/**
 * Contract for contact email validation.
 */
interface EmailValidationServiceInterface
{
    /**
     * Check if email already exists in the system.
     *
     * @param string $email
     * @return bool
     */
    public function exists(string $email): bool;

    /**
     * Check if email is unique for a specific contact (excluding itself).
     *
     * @param string $email
     * @param int $excludeContactId
     * @return bool
     */
    public function isUniqueForContact(string $email, int $excludeContactId): bool;
}
