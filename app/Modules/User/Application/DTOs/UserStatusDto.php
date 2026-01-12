<?php

namespace App\Modules\User\Application\DTOs;

use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\ValueObjects\GenericId;

class UserStatusDto
{
    public function __construct(
        public readonly GenericId $user_id,
        public readonly GenericStatus $status
    ) {}
}
