<?php

namespace App\Modules\Policy\Domain\Contracts;

use App\Models\PetPolicy;
use App\Shared\Domain\ValueObjects\GenericId;

interface PetPolicyRepositoryContract
{
    public function findPetPolicyById(GenericId $pet_id): ?PetPolicy;
    public function findPetById(GenericId $policy_id): ?PetPolicy;
}
