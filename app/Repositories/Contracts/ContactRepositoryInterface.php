<?php

namespace App\Repositories\Contracts;

use App\Models\Contact;

/**
 * Contact repository interface.
 */
interface ContactRepositoryInterface
{
    /**
     * Create a new contact.
     *
     * @param array $data
     * @return Contact
     */
    public function create(array $data): Contact;

    /**
     * Find contact by ID.
     *
     * @param int $id
     * @return Contact|null
     */
    public function findById(int $id): ?Contact;

    /**
     * Find contact by email.
     *
     * @param string $email
     * @return Contact|null
     */
    public function findByEmail(string $email): ?Contact;

    /**
     * Check if email exists.
     *
     * @param string $email
     * @return bool
     */
    public function emailExists(string $email): bool;

    /**
     * Delete a contact.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
