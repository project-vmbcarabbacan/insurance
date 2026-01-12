<?php

namespace App\Modules\User\Domain\Entities;

use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\LowerText;

final class UserEntity
{
    public function __construct(
        public readonly LowerText $name,
        public readonly Email $email,
    ) {}

    public function toArray()
    {
        return  [
            'name' => $this->name->value(),
            'email' => $this->email->value()
        ];
    }
}
