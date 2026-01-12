<?php

namespace App\Modules\User\Application\DTOs;

use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Password;

class UpdatePasswordDto
{
    public function __construct(
        public readonly GenericId $user_id,
        public readonly Password $password,
        public readonly Password $confirm_password
    ) {}
}
