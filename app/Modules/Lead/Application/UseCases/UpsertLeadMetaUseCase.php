<?php

namespace App\Modules\Lead\Application\UseCases;

use App\Models\Lead;
use App\Modules\Lead\Application\Exceptions\LeadMetaUpsertException;
use App\Modules\Lead\Application\Services\LeadMetaService;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Modules\Master\Application\Services\VehiclePrerequisiteService;

class UpsertLeadMetaUseCase
{
    public function __construct(
        protected LeadMetaService $lead_meta_service,
        protected VehiclePrerequisiteService $vehicle_prerequisite_service
    ) {}

    public function execute(Lead $lead, array $data)
    {
        try {
            $make = $this->vehicle_prerequisite_service->make($data['vehicle_year']);
            $model = $this->vehicle_prerequisite_service->model($data['vehicle_year'], $data['vehicle_make_id']);
            $trim = $this->vehicle_prerequisite_service->trim($data['vehicle_year'], $data['vehicle_make_id'], $data['vehicle_model_id']);

            $data['vehicle_make'] = $make->name;
            $data['vehicle_model'] = $model->name;
            $data['vehicle_trim'] = $trim->name;
            $code = LeadProductType::fromValue($lead->insurance_product_code);
            $this->lead_meta_service->updateMeta($lead, $data, $code);
        } catch (\Exception $e) {
            throw new LeadMetaUpsertException();
        }
    }
}
