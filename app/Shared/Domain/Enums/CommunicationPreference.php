<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

/**
 * CommunicationPreference enum
 *
 * Represents CommunicationPreference types used across the domain.
 */
enum CommunicationPreference: string
{
    case CALL = 'call';
    case SMS = 'sms';
    case WHATSAPP = 'whatsapp';
    case EMAIL = 'email';
    case MEETING = 'meeting';
    case FOLLOW_UP = 'follow_up';
    case DOCUMENT_REQUEST = 'document_request';

    /**
     * Get human-readable label for the enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::CALL => 'Call',
            self::SMS => 'Sms',
            self::WHATSAPP => 'Whatsapp',
            self::EMAIL => 'Email',
            self::MEETING => 'Meeting',
            self::FOLLOW_UP => 'Follow Up',
            self::DOCUMENT_REQUEST => 'Document Request',
        };
    }

    /**
     * Validate and return CommunicationPreference enum from string value.
     *
     * @param string $value
     * @return self
     *
     * @throws InvalidValueException If the value does not match any enum case
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom(strtolower($value))
            ?? throw InvalidValueException::withMessage(
                "Invalid communication preference value: {$value}"
            );
    }

    /**
     * Check if the given value exists in the enum.
     *
     * @param string $value
     * @return bool
     */
    public static function exists(string $value): bool
    {
        return self::tryFrom(strtolower($value)) !== null;
    }

    /**
     * Return enum as an array of label/value pairs.
     * Useful for dropdowns or API responses.
     */
    public static function toDropdownArray(): array
    {
        return array_map(
            fn(self $case) => [
                'label' => $case->label(),
                'value' => $case->value,
            ],
            self::cases()
        );
    }

    /**
     * Return enum as an associative array.
     * Useful for mappings or select inputs.
     */
    public static function toLabelArray(): array
    {
        $result = [];

        foreach (self::cases() as $case) {
            $result[$case->value] = $case->label();
        }

        return $result;
    }
}
