<?php

namespace App\Shared\Domain\ValueObjects;

use Carbon\Carbon;
use InvalidArgumentException;

final class GenericDate
{
    private Carbon $value;

    /**
     * Create a BirthDate value object.
     *
     * @param string|Carbon $date
     *
     * @throws InvalidArgumentException If the date is invalid or in the future
     */
    private function __construct(string|Carbon $date, bool $ensureNotFuture)
    {
        if ($ensureNotFuture) {
            $this->ensureNotInFuture();
        }

        $this->value = $this->parseDate($date);
    }

    public static function fromString(string|Carbon $date, bool $ensureNotFuture = true)
    {
        return new self($date, $ensureNotFuture);
    }

    /**
     * Parse and normalize the given date.
     *
     * @param string|Carbon $date
     * @return Carbon
     */
    private function parseDate(string|Carbon $date): Carbon
    {
        if ($date instanceof Carbon) {
            return $date->startOfDay();
        }

        return Carbon::parse($date)->startOfDay();
    }

    /**
     * Ensure birth date is not in the future.
     *
     * @throws InvalidArgumentException
     */
    private function ensureNotInFuture(): void
    {
        if ($this->value->isFuture()) {
            throw new InvalidArgumentException('Birth date cannot be in the future.');
        }
    }

    /**
     * Get the birth date as Carbon instance.
     */
    public function value(): Carbon
    {
        return $this->value->copy();
    }

    /**
     * Get the birth date as a string (Y-m-d).
     */
    public function toString(): string
    {
        return $this->value->toDateString();
    }

    /**
     * Calculate the current age.
     */
    public function age(): int
    {
        return $this->value->age;
    }

    /**
     * Check if the user is at least the given age.
     *
     * @param int $minimumAge
     */
    public function isAtLeast(int $minimumAge): bool
    {
        return $this->age() >= $minimumAge;
    }

    /**
     * Compare two BirthDate value objects.
     */
    public function equals(self $other): bool
    {
        return $this->value->equalTo($other->value);
    }
}
