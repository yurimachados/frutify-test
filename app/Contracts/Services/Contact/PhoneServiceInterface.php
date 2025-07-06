<?php

namespace App\Contracts\Services\Contact;

/**
 * Contract for phone number operations.
 */
interface PhoneServiceInterface
{
    /**
     * Normalize phone number by removing non-numeric characters.
     *
     * @param string $phone
     * @return string Normalized phone number
     */
    public function normalize(string $phone): string;

    /**
     * Validate phone number format.
     *
     * @param string $phone
     * @return bool
     */
    public function isValid(string $phone): bool;
}
