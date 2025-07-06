<?php

namespace App\Contracts\UseCases\Contact;

use App\Exceptions\Contact\ContactNotFoundException;

/**
 * Contract for deleting a contact.
 */
interface DeleteContactUseCaseInterface
{
    /**
     * Execute the delete contact use case.
     *
     * @param int $contactId
     * @return bool
     * @throws ContactNotFoundException When contact doesn't exist
     */
    public function execute(int $contactId): bool;
}
