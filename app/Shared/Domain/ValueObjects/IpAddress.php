<?php

namespace App\Shared\Domain\ValueObjects;

use InvalidArgumentException;

final class IpAddress
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Create IP Address value object
     *
     * @throws InvalidArgumentException
     */
    public static function fromString(string $ip): self
    {
        $ip = trim($ip);

        if (! filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new InvalidArgumentException('Invalid IP address.');
        }

        return new self($ip);
    }

    /**
     * Raw IP string value
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Check if IPv4
     */
    public function isIpv4(): bool
    {
        return filter_var($this->value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
    }

    /**
     * Check if IPv6
     */
    public function isIpv6(): bool
    {
        return filter_var($this->value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
    }

    /**
     * Compare two IP addresses
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
