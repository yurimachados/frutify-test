<?php

namespace App\Exceptions\Contact;

use Exception;

/**
 * Exception thrown when trying to create a contact with an email that already exists.
 */
class EmailAlreadyExistsException extends Exception
{
    public function __construct(string $email)
    {
        parent::__construct("Email '{$email}' already exists");
    }
}
