<?php

namespace App\Contracts\Repositories\Contacts;

use App\Models\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ContactRepositoryInterface extends RepositoryInterface
{
    /**
     * Find contact by email address.
     *
     * @param string $email
     * @return Contact|null
     */
    public function findByEmail(string $email): ?Contact;

    /**
     * Find contact by phone number.
     *
     * @param string $phone
     * @return Contact|null
     */
    public function findByPhone(string $phone): ?Contact;

    /**
     * Check if email already exists.
     *
     * @param string $email
     * @return bool
     */
    public function emailExists(string $email): bool;

    /**
     * Search contacts by name, email or phone.
     *
     * @param string $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $search, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get paginated contacts ordered by name.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateOrdered(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get paginated trashed contacts.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getTrashedPaginated(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all contacts including trashed.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator;

    /**
     * Restore a soft-deleted contact.
     *
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool;

    /**
     * Force delete a contact permanently.
     *
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool;
}
