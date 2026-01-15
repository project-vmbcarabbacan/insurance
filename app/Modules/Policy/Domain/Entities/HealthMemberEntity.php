<?php

namespace App\Modules\Policy\Domain\Entities;

use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

final class HealthMemberEntity
{
    public function __construct(
        public readonly GenericId $health_policy_id,
        public readonly LowerText $first_name,
        public readonly LowerText $last_name,
        public readonly GenericDate $date_of_birth,
        public readonly LowerText $relationship
    ) {}

    public function toArray()
    {
        return [
            'health_policy_id' => $this->health_policy_id->value(),
            'first_name' => $this->first_name->value(),
            'last_name' => $this->last_name->value(),
            'date_of_birth' => $this->date_of_birth->value(),
            'relationship' => $this->relationship->value(),
        ];
    }
}
