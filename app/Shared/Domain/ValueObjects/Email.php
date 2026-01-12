<?php

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidValueException;

final class Email
{
    private string $value;

    private function __construct(string $email)
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw InvalidValueException::withMessage("Invalid email address");
        }

        $this->value = strtolower($email);
    }

    public static function fromString(string $email): self
    {
        return new self($email);
    }

    public function value(): string
    {
        return $this->value;
    }
}
