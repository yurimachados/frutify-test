<?php

namespace App\UseCases\Contact;

use App\Repositories\Contracts\ContactRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * List contacts with pagination.
 *
 * Handles contact listing with business rules for
 * pagination limits and default values.
 */
class ListContactsUseCase
{
    public function __construct(
        private ContactRepositoryInterface $repository
    ) {}

    /**
     * Get paginated contacts list.
     *
     * @param int $perPage Items per page (1-100, default: 10)
     * @param string|null $search Search term for filtering contacts
     * @return LengthAwarePaginator Paginated contacts
     */
    public function execute(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $validatedPerPage = max(1, min($perPage, 100));

        return $this->repository->getPaginated($validatedPerPage, $search);
    }
}
