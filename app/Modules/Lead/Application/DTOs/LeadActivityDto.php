<?php

namespace App\Modules\Lead\Application\DTOs;

use App\Shared\Domain\Enums\LeadActivityType;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

class LeadActivityDto
{
    public function __construct(
        public readonly GenericId $lead_id,
        public readonly LeadActivityType $type,
        public readonly LowerText $performed_by_name,
        public readonly string|array|null $notes = null,
        public readonly ?GenericId $performed_by_id = null,
    ) {}
}
