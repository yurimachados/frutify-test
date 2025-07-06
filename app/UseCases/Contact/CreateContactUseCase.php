<?php

namespace App\UseCases\Contact;

use App\Contracts\Repositories\Contacts\ContactRepositoryInterface;
use App\Contracts\Services\Contact\EmailValidationServiceInterface;
use App\Contracts\Services\Contact\PhoneServiceInterface;
use App\Contracts\UseCases\Contact\CreateContactUseCaseInterface;
use App\DTOs\CreateContactDto;
use App\Exceptions\Contact\EmailAlreadyExistsException;
use App\Models\Contact;
/**
 * Use case for creating a new contact.
 *
 * Orchestrates the contact creation process by validating
 * business rules and delegating specific operations to services.
 */
class CreateContactUseCase implements CreateContactUseCaseInterface
{
    public function __construct(
        private ContactRepositoryInterface $contactRepository,
        private EmailValidationServiceInterface $emailValidationService,
        private PhoneServiceInterface $phoneService
    ) {}

    /**
     * Execute the create contact use case.
     *
     * @param CreateContactDto $dto
     * @return Contact
     * @throws EmailAlreadyExistsException
     */
    public function execute(CreateContactDto $dto): Contact
    {
        // Business rule: Check if email already exists
        if ($this->emailValidationService->exists($dto->email)) {
            throw new EmailAlreadyExistsException($dto->email);
        }

        // Create contact data with normalized phone
        $contactData = [
            'name' => $dto->name,
            'email' => $dto->email,
            'phone' => $this->phoneService->normalize($dto->phone)
        ];

        // Create and return the contact
        return $this->contactRepository->create($contactData);
    }
}
