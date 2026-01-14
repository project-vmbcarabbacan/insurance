<?php

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidValueException;

final class Amount
{
    private int $amount;      // stored in minor units (e.g. cents)
    private string $currency; // ISO 4217 (USD, EUR, etc.)

    public function __construct(int $amount, string $currency = 'AED')
    {
        if ($amount < 0) {
            throw InvalidValueException::withMessage('Price amount cannot be negative.');
        }

        if (! preg_match('/^[A-Z]{3}$/', $currency)) {
            throw InvalidValueException::withMessage('Invalid currency code.');
        }

        $this->amount = $amount;
        $this->currency = strtoupper($currency);
    }

    /** Named constructor for decimal input (e.g. 10.99) */
    public static function fromDecimal(float|string $amount, string $currency = 'AED'): self
    {
        return new self(
            (int) round(((float) $amount) * 100),
            $currency
        );
    }

    public static function fromAmount(string|int $amount, string $currency = 'AED'): self
    {
        return new self((int) $amount, $currency);
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    /** Decimal representation (e.g. 10.99) */
    public function decimal(): string
    {
        return number_format($this->amount / 100, 2, '.', '');
    }

    public function formatted(): string
    {
        return "{$this->currency} {$this->decimal()}";
    }

    public function format(): string
    {
        return number_format($this->amount, 2);
    }

    /** Arithmetic (returns new VO) */
    public function add(self $other): self
    {
        $this->assertSameCurrency($other);

        return new self(
            $this->amount + $other->amount,
            $this->currency
        );
    }

    public function subtract(self $other): self
    {
        $this->assertSameCurrency($other);

        if ($other->amount > $this->amount) {
            throw InvalidValueException::withMessage('Resulting price cannot be negative.');
        }

        return new self(
            $this->amount - $other->amount,
            $this->currency
        );
    }

    public function multiply(int|float $multiplier): self
    {
        return new self(
            (int) round($this->amount * $multiplier),
            $this->currency
        );
    }

    public function equals(self $other): bool
    {
        return $this->amount === $other->amount
            && $this->currency === $other->currency;
    }

    private function assertSameCurrency(self $other): void
    {
        if ($this->currency !== $other->currency) {
            throw InvalidValueException::withMessage('Currency mismatch.');
        }
    }

    public function __toString(): string
    {
        return $this->formatted();
    }
}
