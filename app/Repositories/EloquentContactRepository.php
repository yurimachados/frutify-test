<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Repositories\Contracts\ContactRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Eloquent-based implementation of contact repository.
 *
 * Provides contact data access operations using Laravel's Eloquent ORM,
 * implementing the ContactRepositoryInterface contract.
 */
class EloquentContactRepository implements ContactRepositoryInterface
{
    /**
     * Create a new contact record.
     *
     * @param array $contactData Contact attributes
     * @return Contact Created contact instance
     */
    public function create(array $contactData): Contact
    {
        return Contact::create($contactData);
    }

    /**
     * Find contact by email address.
     *
     * @param string $emailAddress Contact email address
     * @return Contact|null Contact instance or null if not found
     */
    public function findByEmail(string $emailAddress): ?Contact
    {
        return Contact::where('email', $emailAddress)->first();
    }

    /**
     * Check if email address is already registered.
     *
     * @param string $emailAddress Email address to verify
     * @return bool True if email exists, false otherwise
     */
    public function emailExists(string $emailAddress): bool
    {
        return Contact::where('email', $emailAddress)->exists();
    }

    /**
     * Find contact by unique identifier.
     *
     * @param int $contactId Contact identifier
     * @return Contact|null Contact instance or null if not found
     */
    public function findById(int $contactId): ?Contact
    {
        return Contact::find($contactId);
    }

    /**
     * Update existing contact record.
     *
     * @param int $contactId Contact identifier
     * @param array $contactData Updated contact attributes
     * @return Contact Fresh instance of updated contact
     */
    public function update(int $contactId, array $contactData): Contact
    {
        $contact = Contact::findOrFail($contactId);
        $contact->update($contactData);
        return $contact->fresh();
    }

    /**
     * Get paginated list of contacts.
     *
     * @param int $perPage Number of contacts per page
     * @return LengthAwarePaginator
     */
    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Contact::paginate($perPage);
    }

    /**
     * Delete contact by identifier.
     *
     * @param int $contactId Contact identifier
     * @return bool True if deletion was successful, false if contact not found
     */
    public function delete(int $contactId): bool
    {
        $contactToDelete = Contact::find($contactId);

        if (!$contactToDelete) {
            return false;
        }

        return $contactToDelete->delete();
    }
}
