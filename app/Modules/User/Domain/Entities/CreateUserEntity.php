<?php

namespace App\Modules\User\Domain\Entities;

use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Password;
use App\Shared\Domain\ValueObjects\LowerText;

final class CreateUserEntity
{
    public function __construct(
        public readonly LowerText $name,
        public readonly Email $email,
        public readonly Password $password,
        public readonly GenericId $role_id,
        public readonly LowerText $status
    ) {}

    public function toArray()
    {
        return  [
            'name' => $this->name->value(),
            'email' => $this->email->value(),
            'password' => $this->password->value(),
            'role_id' => $this->role_id->value(),
            'status' => $this->status->value(),
        ];
    }
}
