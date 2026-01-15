<?php

namespace App\Modules\Policy\Domain\Entities;

use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

final class PetPolicyEntity
{
    public function __construct(
        public readonly GenericId $policy_id,
        public readonly LowerText $pet_name,
        public readonly LowerText $species,
        public readonly LowerText $breed,
        public readonly int $age,
        public readonly LowerText $microchip_number,
    ) {}

    public function toArray()
    {
        return [
            'policy_id' => $this->policy_id->value(),
            'pet_name' => $this->pet_name->value(),
            'species' => $this->species->value(),
            'breed' => $this->breed->value(),
            'age' => $this->age,
            'microchip_number' => $this->microchip_number->value(),
        ];
    }
}
