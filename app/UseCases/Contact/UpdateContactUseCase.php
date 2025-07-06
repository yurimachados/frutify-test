<?php

namespace App\UseCases\Contact;

use App\Contracts\Services\Contact\EmailValidationServiceInterface;
use App\Contracts\Services\Contact\PhoneServiceInterface;
use App\Contracts\UseCases\Contact\UpdateContactUseCaseInterface;
use App\DTOs\UpdateContactDto;
use App\Exceptions\Contact\ContactNotFoundException;
use App\Exceptions\Contact\EmailAlreadyExistsException;
use App\Models\Contact;
use App\Contracts\Repositories\Contacts\ContactRepositoryInterface;

/**
 * Business logic for updating existing contacts.
 *
 * Handles email uniqueness validation and contact data updates
 * while maintaining business rules and data integrity.
 */
class UpdateContactUseCase implements UpdateContactUseCaseInterface
{
    public function __construct(
        private ContactRepositoryInterface $contactRepository,
        private EmailValidationServiceInterface $emailValidationService,
        private PhoneServiceInterface $phoneService
    ) {}

    /**
     * Execute contact update operation.
     *
     * Validates email uniqueness (excluding current contact) and
     * updates contact data with normalized phone number.
     *
     * @param UpdateContactDto $contactData
     * @return Contact Updated contact instance
     * @throws ContactNotFoundException When contact doesn't exist
     * @throws EmailAlreadyExistsException When email already exists for another contact
     */
    public function execute(UpdateContactDto $contactData): Contact
    {
        // Find the contact first
        $contact = $this->contactRepository->find($contactData->id);

        if (!$contact) {
            throw new ContactNotFoundException($contactData->id);
        }

        // Business rule: Validate email uniqueness (excluding current contact)
        if (!$this->emailValidationService->isUniqueForContact($contactData->email, $contactData->id)) {
            throw new EmailAlreadyExistsException($contactData->email);
        }

        // Update the contact
        $this->contactRepository->update($contact, [
            'name' => $contactData->name,
            'email' => $contactData->email,
            'phone' => $this->phoneService->normalize($contactData->phone),
        ]);

        // Return the updated contact
        return $contact->fresh();
    }
}
