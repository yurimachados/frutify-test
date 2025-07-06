<?php

namespace App\Services\Contact;

use App\Contracts\Services\Contact\PhoneServiceInterface;

/**
 * Service responsible for phone number operations.
 *
 * Centralizes phone number normalization and validation logic
 * to avoid code duplication across Use Cases.
 */
class PhoneService implements PhoneServiceInterface
{
    /**
     * Normalize phone number by removing non-numeric characters.
     *
     * @param string $phone
     * @return string Normalized phone number
     */
    public function normalize(string $phone): string
    {
        return preg_replace('/\D/', '', $phone);
    }

    /**
     * Validate phone number format.
     *
     * @param string $phone
     * @return bool
     */
    public function isValid(string $phone): bool
    {
        $normalizedPhone = $this->normalize($phone);

        // Business rule: Phone must have at least 10 digits
        return strlen($normalizedPhone) >= 10;
    }
}
