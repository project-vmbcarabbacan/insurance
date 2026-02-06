<?php

namespace App\Modules\Lead\Application\Services;

use App\Models\Lead;
use App\Modules\Lead\Domain\Contracts\LeadMetaRepositoryContract;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Modules\Lead\Infrastructure\Factories\LeadFactory;
use App\Shared\Domain\ValueObjects\GenericId;

class LeadMetaService
{

    public function __construct(
        protected LeadFactory $lead_factory
    ) {}

    public function addMeta(GenericId $leadId, array $data, LeadProductType $type)
    {
        $repo = $this->lead_factory->make($type);
        $repo->addLeadMeta($leadId, $data);
    }

    public function updateMeta(Lead $lead, array $data, LeadProductType $type)
    {
        $repo = $this->lead_factory->make($type);
        $repo->updateLeadMeta($lead, $data);
    }

    public function byCustomerId(GenericId $customerId, LeadProductType $type)
    {
        $repo = $this->lead_factory->make($type);
        return $repo->getLeadByCustomerId($customerId);
    }

    public function byLeadId(GenericId $leadId, LeadProductType $type)
    {
        $repo = $this->lead_factory->make($type);
        return $repo->getLeadByLeadId($leadId);
    }
}
