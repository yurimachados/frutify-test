<?php

namespace App\Contracts\UseCases\Contact;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Contract for listing contacts with pagination.
 */
interface ListContactsUseCaseInterface
{
    /**
     * Get paginated contacts list.
     *
     * @param int $perPage Items per page (1-100, default: 10)
     * @param string|null $search Search term for filtering contacts
     * @return LengthAwarePaginator Paginated contacts
     */
    public function execute(int $perPage = 10, ?string $search = null): LengthAwarePaginator;
}
