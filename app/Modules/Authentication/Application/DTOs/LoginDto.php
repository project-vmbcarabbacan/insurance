<?php

namespace App\Modules\Authentication\Application\DTOs;

use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\Password;
use App\Shared\Domain\ValueObjects\IpAddress;

class LoginDto
{
    public function __construct(
        public readonly Email $email,
        public readonly Password $password,
        public readonly IpAddress $ip_address
    ) {}
}
