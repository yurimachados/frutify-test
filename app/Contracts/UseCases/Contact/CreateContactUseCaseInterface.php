<?php

namespace App\Contracts\UseCases\Contact;

use App\DTOs\CreateContactDto;
use App\Exceptions\Contact\EmailAlreadyExistsException;
use App\Models\Contact;

/**
 * Contract for creating a new contact.
 */
interface CreateContactUseCaseInterface
{
    /**
     * Execute the create contact use case.
     *
     * @param CreateContactDto $dto
     * @return Contact
     * @throws EmailAlreadyExistsException
     */
    public function execute(CreateContactDto $dto): Contact;
}
