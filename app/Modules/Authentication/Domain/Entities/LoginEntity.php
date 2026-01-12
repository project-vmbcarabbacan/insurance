<?php

namespace App\Modules\Authentication\Domain\Entities;

use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\Password;
use App\Shared\Domain\ValueObjects\IpAddress;

final class LoginEntity
{
    public function __construct(
        public readonly Email $email,
        public readonly Password $password,
        public readonly IpAddress $ip_address
    ) {}
}
