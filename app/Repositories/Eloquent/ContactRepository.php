<?php

namespace App\Repositories\Eloquent;

use App\Models\Contact;
use App\Contracts\Repositories\Contacts\ContactRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContactRepository extends BaseRepository implements ContactRepositoryInterface
{
    public function __construct(Contact $model)
    {
        parent::__construct($model);
    }

    /**
     * Find contact by email address.
     *
     * @param string $email
     * @return Contact|null
     */
    public function findByEmail(string $email): ?Contact
    {
        return $this->findBy('email', $email);
    }

    /**
     * Find contact by phone number.
     *
     * @param string $phone
     * @return Contact|null
     */
    public function findByPhone(string $phone): ?Contact
    {
        return $this->findBy('phone', preg_replace('/\D/', '', $phone));
    }

    /**
     * Check if email already exists.
     *
     * @param string $email
     * @return bool
     */
    public function emailExists(string $email): bool
    {
        return $this->model->where('email', $email)->exists();
    }

    /**
     * Search contacts by name, email or phone.
     *
     * @param string $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $search, int $perPage = 15): LengthAwarePaginator
    {
        $searchTerm = '%' . strtolower($search) . '%';

        return $this->model
            ->where(function($query) use ($searchTerm, $search) {
                $query->whereRaw('LOWER(name) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(email) LIKE ?', [$searchTerm])
                      ->orWhere('phone', 'like', '%' . $search . '%');
            })
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get paginated contacts ordered by name.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateOrdered(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->orderBy('name')->paginate($perPage);
    }

    /**
     * Get paginated trashed contacts.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getTrashedPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->onlyTrashed()->orderBy('name')->paginate($perPage);
    }

    /**
     * Get all contacts including trashed.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->withTrashed()->orderBy('name')->paginate($perPage);
    }

    /**
     * Restore a soft-deleted contact.
     *
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool
    {
        $contact = $this->model->withTrashed()->find($id);
        return $contact ? $contact->restore() : false;
    }

    /**
     * Force delete a contact permanently.
     *
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool
    {
        $contact = $this->model->withTrashed()->find($id);
        return $contact ? $contact->forceDelete() : false;
    }
}
