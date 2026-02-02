<?php

namespace App\Modules\User\Domain\Entities;

use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

final class UserEntity
{
    public function __construct(
        public readonly LowerText $name,
        public readonly Email $email,
        public readonly GenericId $role_id,
    ) {}

    public function toArray()
    {
        return  [
            'name' => $this->name->value(),
            'email' => $this->email->value(),
            'role_id' => $this->role_id->value(),
        ];
    }
}
