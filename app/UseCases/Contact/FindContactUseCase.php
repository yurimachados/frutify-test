<?php

namespace App\UseCases\Contact;

use App\Models\Contact;
use App\Repositories\Contracts\ContactRepositoryInterface;

/**
 * Find a specific contact by ID.
 *
 * Handles contact retrieval with potential for future business
 * logic like access control or audit logging.
 */
class FindContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $repository
    ) {}

    /**
     * Find contact by ID.
     *
     * @param int $id Contact identifier
     * @return Contact|null Found contact or null if not exists
     */
    public function execute(int $id): ?Contact
    {
        return $this->repository->findById($id);
    }
}
