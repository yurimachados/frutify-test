<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Repositories\Contracts\ContactRepositoryInterface;

/**
 * Eloquent implementation of ContactRepositoryInterface.
 */
class EloquentContactRepository implements ContactRepositoryInterface
{
    /**
     * Create a new contact.
     *
     * @param array $data
     * @return Contact
     */
    public function create(array $data): Contact
    {
        return Contact::create($data);
    }

    /**
     * Find contact by email.
     *
     * @param string $email
     * @return Contact|null
     */
    public function findByEmail(string $email): ?Contact
    {
        return Contact::where('email', $email)->first();
    }

    /**
     * Check if email exists.
     *
     * @param string $email
     * @return bool
     */
    public function emailExists(string $email): bool
    {
        return Contact::where('email', $email)->exists();
    }
}
