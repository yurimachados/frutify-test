<?php

namespace App\UseCases\Contact;

use App\Models\Contact;
use App\Repositories\Contracts\ContactRepositoryInterface;

/**
 * Use case for deleting a contact.
 */
class DeleteContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $contactRepository
    ) {}

    /**
     * Execute the delete contact use case.
     *
     * @param int $contactId
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function execute(int $contactId): bool
    {
        // Business rule: Check if contact exists
        $contact = $this->contactRepository->find($contactId);

        if (!$contact) {
            throw new \InvalidArgumentException('Contact not found');
        }

        // Delete the contact
        return $this->contactRepository->delete($contact);
    }
}
