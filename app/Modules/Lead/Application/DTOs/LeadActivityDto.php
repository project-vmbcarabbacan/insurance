<?php

namespace App\Modules\Lead\Application\DTOs;

use App\Shared\Domain\Enums\LeadActivityType;
use App\Shared\Domain\ValueObjects\GenericId;

class LeadActivityDto
{
    public function __construct(
        public readonly GenericId $lead_id,
        public readonly LeadActivityType $type,
        public readonly string $notes
    ) {}
}
