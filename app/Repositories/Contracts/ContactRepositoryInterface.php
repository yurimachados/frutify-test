<?php

namespace App\Repositories\Contracts;

use App\Models\Contact;

/**
 * Contract defining contact data access operations.
 *
 * Establishes the interface for contact repository implementations,
 * ensuring consistent data access patterns across the application.
 */
interface ContactRepositoryInterface
{
    /**
     * Create a new contact record.
     *
     * @param array $contactData Contact attributes
     * @return Contact Created contact instance
     */
    public function create(array $contactData): Contact;

    /**
     * Find contact by unique identifier.
     *
     * @param int $contactId Contact identifier
     * @return Contact|null Contact instance or null if not found
     */
    public function findById(int $contactId): ?Contact;

    /**
     * Find contact by email address.
     *
     * @param string $emailAddress Contact email address
     * @return Contact|null Contact instance or null if not found
     */
    public function findByEmail(string $emailAddress): ?Contact;

    /**
     * Check if email address is already registered.
     *
     * @param string $emailAddress Email address to verify
     * @return bool True if email exists, false otherwise
     */
    public function emailExists(string $emailAddress): bool;

    /**
     * Update existing contact record.
     *
     * @param int $contactId Contact identifier
     * @param array $contactData Updated contact attributes
     * @return Contact Updated contact instance
     */
    public function update(int $contactId, array $contactData): Contact;

    /**
     * Delete contact by identifier.
     *
     * @param int $contactId Contact identifier
     * @return bool True if deletion was successful, false otherwise
     */
    public function delete(int $contactId): bool;
}
