<?php

namespace App\Shared\Domain\ValueObjects;

use InvalidArgumentException;

final class Phone
{
    /**
     * Phone number stored in E.164 format (e.g. +971561234567)
     */
    private string $value;
    private string $phoneNumber;
    private string $countryCode;

    /**
     * Create a Phone value object.
     *
     * Accepts various formats and normalizes to E.164.
     *
     * @param string $phone
     * @param string $defaultCountryCode Default country code (e.g. "+63", "+971")
     *
     * @throws InvalidArgumentException
     */
    private function __construct(string $phone, string $defaultCountryCode)
    {
        $this->value = $this->normalize($phone, $defaultCountryCode);

        $this->validate();
    }

    public static function fromString(string $phone, string $defaultCountryCode = '+971')
    {
        return new self($phone, $defaultCountryCode);
    }

    /**
     * Normalize phone number to E.164 format.
     */
    private function normalize(string $phone, string $defaultCountryCode): string
    {
        // Remove spaces, dashes, parentheses
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        // If phone starts with 0, remove it
        $phone = ltrim($phone, '0');

        $this->phoneNumber = $phone;

        // Add country code if missing
        if (! str_starts_with($phone, '+')) {
            $phone = $defaultCountryCode . $phone;
        }

        $this->countryCode = '+' . ltrim($defaultCountryCode, '+');

        return $phone;
    }

    /**
     * Validate phone number format (E.164).
     *
     * @throws InvalidArgumentException
     */
    private function validate(): void
    {
        if (! preg_match('/^\+[1-9]\d{7,14}$/', $this->value)) {
            throw new InvalidArgumentException('Invalid phone number format.');
        }
    }

    /**
     * Get the phone number value.
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Get the phone number value.
     */
    public function phoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * Get the phone number country code.
     */
    public function countryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * Return phone number as string.
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * Compare two phone value objects.
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
