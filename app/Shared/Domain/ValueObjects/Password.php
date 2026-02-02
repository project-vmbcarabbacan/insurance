<?php

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidValueException;

final class Password
{
    private string $hashed;
    private string $plain;

    private function __construct(string $password)
    {
        $this->hashed = password_hash($password, PASSWORD_BCRYPT);
        $this->plain = $password;
    }

    public static function fromPlain(string $plain): self
    {
        self::assertStrong($plain);

        return new self($plain, PASSWORD_BCRYPT);
    }

    public static function fromHash(string $hashed): self
    {
        if (!self::isValidHash($hashed)) {
            throw new InvalidValueException('Invalid password hash');
        }

        return new self($hashed);
    }

    public function verify(string $plain): bool
    {
        return password_verify($plain, $this->hashed);
    }

    public function value(): string
    {
        return $this->hashed;
    }

    public function plain(): string
    {
        return $this->plain;
    }

    public function equals(self $other): bool
    {
        return $this->plain === $other->plain;
    }

    private static function assertStrong(string $plain): void
    {
        if (strlen($plain) < 8) {
            throw new InvalidValueException('Password must be at least 8 characters');
        }

        if (!preg_match('/[A-Z]/', $plain)) {
            throw new InvalidValueException('Password must contain an uppercase letter');
        }

        if (!preg_match('/[a-z]/', $plain)) {
            throw new InvalidValueException('Password must contain a lowercase letter');
        }

        if (!preg_match('/[0-9]/', $plain)) {
            throw new InvalidValueException('Password must contain a number');
        }

        if (!preg_match('/[\W_]/', $plain)) {
            throw new InvalidValueException('Password must contain a special character');
        }
    }

    private static function isValidHash(string $hash): bool
    {
        return password_get_info($hash)['algo'] !== null;
    }
}
