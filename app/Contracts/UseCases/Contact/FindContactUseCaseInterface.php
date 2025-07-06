<?php

namespace App\Contracts\UseCases\Contact;

use App\Contracts\UseCases\UseCaseInterface;
use App\Models\Contact;

/**
 * Contract for finding a specific contact by ID.
 */
interface FindContactUseCaseInterface
{
    /**
     * Find contact by ID.
     *
     * @param int $id Contact identifier
     * @return Contact|null Found contact or null if not exists
     */
    public function execute(int $id): ?Contact;
}
