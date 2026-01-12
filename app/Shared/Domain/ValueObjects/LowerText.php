<?php

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidValueException;

final class LowerText
{
    private string $value;

    private function __construct(string $value)
    {
        if (empty($value)) {
            throw InvalidValueException::withMessage('Invalid string');
        }

        $this->value = strtolower($value);
    }

    public static function fromString(string $text): self
    {
        return new self($text);
    }

    public function value(): string
    {
        return $this->value;
    }
}
