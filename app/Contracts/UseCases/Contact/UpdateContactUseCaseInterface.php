<?php

namespace App\Contracts\UseCases\Contact;

use App\Contracts\UseCases\UseCaseInterface;
use App\DTOs\UpdateContactDto;
use App\Exceptions\Contact\ContactNotFoundException;
use App\Exceptions\Contact\EmailAlreadyExistsException;
use App\Models\Contact;

/**
 * Contract for updating an existing contact.
 */
interface UpdateContactUseCaseInterface
{
    /**
     * Execute contact update operation.
     *
     * @param UpdateContactDto $contactData
     * @return Contact Updated contact instance
     * @throws ContactNotFoundException When contact doesn't exist
     * @throws EmailAlreadyExistsException When email already exists for another contact
     */
    public function execute(UpdateContactDto $contactData): Contact;
}
