<?php

namespace App\Modules\Authentication\Application\DTOs;

use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\Enums\RoleSlug;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\Password;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\IpAddress;
use App\Shared\Domain\ValueObjects\LowerText;
use DomainException;

class RegisterDto
{

    public function __construct(
        public readonly LowerText $name,
        public readonly Email $email,
        public readonly Password $password,
        public readonly Password $confirm_password,
        public readonly ?GenericId $role_id = get_role_id_by_slug(RoleSlug::CUSTOMER),
        public readonly ?LowerText $status = GenericStatus::ACTIVE,
        public readonly ?IpAddress $ip_address = ''
    ) {
        if ($this->password->value() !== $this->confirm_password->value()) {
            throw new DomainException("Password and confirm password do not match.");
        }
    }
}
