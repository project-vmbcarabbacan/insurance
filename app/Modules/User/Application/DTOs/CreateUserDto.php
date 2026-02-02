<?php

namespace App\Modules\User\Application\DTOs;

use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\LowerText;
use App\Shared\Domain\ValueObjects\Password;

class CreateUserDto
{
    public function __construct(
        public readonly LowerText $name,
        public readonly Email $email,
        public readonly Password $password,
        public readonly LowerText $role,
        public readonly GenericStatus $status = GenericStatus::ACTIVE
    ) {}
}
