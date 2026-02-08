<?php

namespace App\Modules\Lead\Application\UseCases;

use App\Modules\Lead\Application\Exceptions\LeadUuidNotFoundException;
use App\Modules\Lead\Application\Services\LeadMetaService;
use App\Modules\Lead\Application\Services\LeadService;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;

class LeadByUuidUseCase
{

    public function __construct(
        protected LeadService $lead_service,
        protected LeadMetaService $lead_meta_service
    ) {}

    public function execute(Uuid $uuid, array $map)
    {
        $lead = $this->lead_service->getLeadByUuid($uuid);

        if (!$lead) {
            throw new LeadUuidNotFoundException();
        }
        $product_code = LeadProductType::fromValue($lead->insurance_product_code);

        // Only merge member keys if product is NOT 'vehicle'
        if ($product_code->value !== 'vehicle') {
            $leadId = GenericId::fromId($lead->id);
            $map = array_merge($map, $this->lead_meta_service->memberKeys($leadId, $product_code));
        }

        return $this->lead_meta_service->byLeadId($uuid, $product_code, $map);
    }
}
