<?php

namespace App\Modules\Policy\Domain\Contracts;

use App\Models\HomePolicy;
use App\Shared\Domain\ValueObjects\GenericId;

interface HomePolicyRepositoryContract
{
    public function findHomePolicyById(GenericId $home_id): ?HomePolicy;
    public function findHomeById(GenericId $policy_id): ?HomePolicy;
}
