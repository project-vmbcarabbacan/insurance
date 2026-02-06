<?php

namespace App\Modules\Lead\Application\Services;

use App\Models\Lead;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Modules\Lead\Infrastructure\Factories\LeadFactory;
use App\Shared\Domain\ValueObjects\GenericId;

class VehicleLeadService
{
    public function __construct(
        private LeadFactory $lead_factory
    ) {}

    public function addMeta(GenericId $leadId, array $data): void
    {
        $this->lead_factory->make(LeadProductType::VEHICLE)->addLeadMeta($leadId, $data);
    }

    public function updateMeta(Lead $lead, array $data): void
    {
        $this->lead_factory->make(LeadProductType::VEHICLE)->updateLeadMeta($lead, $data);
    }

    public function byCustomerId(GenericId $customerId): void
    {
        $this->lead_factory->make(LeadProductType::VEHICLE)->getLeadByCustomerId($customerId);
    }

    public function byLeadId(GenericId $leadId): void
    {
        $this->lead_factory->make(LeadProductType::VEHICLE)->getLeadByLeadId($leadId);
    }
}
