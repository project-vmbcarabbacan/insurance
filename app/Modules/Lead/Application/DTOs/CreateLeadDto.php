<?php

namespace App\Modules\Lead\Application\DTOs;

use App\Shared\Domain\Enums\CustomerSource;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

class CreateLeadDto
{
    public function __construct(
        public readonly LowerText $code,
        public readonly CustomerSource $source,
        public readonly LeadStatus $status,
        public readonly GenericDate $due_date,
        public readonly ?GenericId $customer_id = null,
        public readonly ?GenericId $assigned_agent_id = null
    ) {}

    public function withCustomerId(GenericId $customerId): self
    {
        return new self(
            $this->code,
            $this->source,
            $this->status,
            $this->due_date,
            $customerId,
            $this->assigned_agent_id
        );
    }
}
