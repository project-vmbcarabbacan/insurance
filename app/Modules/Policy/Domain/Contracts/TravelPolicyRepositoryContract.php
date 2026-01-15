<?php

namespace App\Modules\Policy\Domain\Contracts;

use App\Models\TravelPolicy;
use App\Shared\Domain\ValueObjects\GenericId;

interface TravelPolicyRepositoryContract
{
    public function findTravelPolicyById(GenericId $travel_id): ?TravelPolicy;
    public function findTravelById(GenericId $policy_id): ?TravelPolicy;
}
