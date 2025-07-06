<?php

namespace App\Exceptions\Contact;

use Exception;

/**
 * Exception thrown when trying to operate on a contact that doesn't exist.
 */
class ContactNotFoundException extends Exception
{
    public function __construct(int $contactId)
    {
        parent::__construct("Contact with ID '{$contactId}' not found");
    }
}
