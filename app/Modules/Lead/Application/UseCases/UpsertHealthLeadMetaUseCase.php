<?php

namespace App\Modules\Lead\Application\UseCases;

use App\Models\Lead;
use App\Modules\Lead\Application\Exceptions\LeadMetaUpsertException;
use App\Modules\Lead\Application\Services\LeadMetaService;
use App\Modules\Lead\Domain\Enums\LeadProductType;

class UpsertHealthLeadMetaUseCase
{
    public function __construct(
        protected LeadMetaService $lead_meta_service,
    ) {}

    public function execute(Lead $lead, array $data)
    {
        try {
            $code = LeadProductType::fromValue($lead->insurance_product_code);
            $this->lead_meta_service->updateMeta($lead, $data, $code);
        } catch (\Exception $e) {
            throw new LeadMetaUpsertException();
        }
    }
}
