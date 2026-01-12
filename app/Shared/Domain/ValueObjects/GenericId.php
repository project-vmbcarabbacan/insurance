<?php

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidValueException;

final class GenericId
{
    private int $value;

    private function __construct(int $value)
    {
        if ($value <= 0) {
            throw InvalidValueException::withMessage("Invalid id");
        }

        $this->value = $value;
    }

    public static function fromId(int $value): self
    {
        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }
}
