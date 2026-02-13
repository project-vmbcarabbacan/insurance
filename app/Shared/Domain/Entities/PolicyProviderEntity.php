<?php

namespace App\Shared\Domain\Entities;

use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\LowerText;

class PolicyProviderEntity
{
    public function __construct(
        public readonly string $code,
        public readonly LowerText $name,
        public readonly Email $email,
        public readonly string $phone
    ) {}

    public function toArray()
    {
        return [
            'code' => $this->code,
            'name' => $this->name->value(),
            'contact_email' => $this->email->value(),
            'contact_phone' => $this->phone
        ];
    }
}
