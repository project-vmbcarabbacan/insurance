<?php

namespace App\Modules\User\Application\DTOs;

use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

class UpdateUserDto
{
    public function __construct(
        public readonly GenericId $user_id,
        public readonly LowerText $name,
        public readonly Email $email,
        public readonly ?LowerText $role,
    ) {}
}
