<?php

namespace App\UseCases\Contact;

use App\Contracts\UseCases\Contact\DeleteContactUseCaseInterface;
use App\Exceptions\Contact\ContactNotFoundException;
use App\Contracts\Repositories\Contacts\ContactRepositoryInterface;

/**
 * Use case for deleting a contact.
 *
 * Handles contact deletion with proper validation and
 * business rule enforcement.
 */
class DeleteContactUseCase implements DeleteContactUseCaseInterface
{
    public function __construct(
        private ContactRepositoryInterface $contactRepository
    ) {}

    /**
     * Execute the delete contact use case.
     *
     * @param int $contactId
     * @return bool
     * @throws ContactNotFoundException When contact doesn't exist
     */
    public function execute(int $contactId): bool
    {
        // Business rule: Check if contact exists
        $contact = $this->contactRepository->find($contactId);

        if (!$contact) {
            throw new ContactNotFoundException($contactId);
        }

        // Delete the contact (soft delete)
        return $this->contactRepository->delete($contact);
    }
}
