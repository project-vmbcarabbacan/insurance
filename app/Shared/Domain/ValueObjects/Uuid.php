<?php

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidValueException;

final class Uuid
{
    private string $value;

    public function __construct(string $uuid)
    {
        if (!preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-7][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $uuid
        )) {
            throw new InvalidValueException("Invalid UUID: {$uuid}");
        }

        $this->value = $uuid;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function generate(): self
    {
        return new self(self::v4());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private static function v4(): string
    {
        $data = random_bytes(16);

        // Version 4
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        // Variant RFC 4122
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
