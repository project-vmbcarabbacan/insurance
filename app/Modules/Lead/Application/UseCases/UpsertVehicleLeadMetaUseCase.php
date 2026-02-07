<?php

namespace App\Modules\Lead\Application\UseCases;

use App\Models\Lead;
use App\Modules\Lead\Application\Exceptions\LeadMetaUpsertException;
use App\Modules\Lead\Application\Services\LeadMetaService;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Modules\Master\Application\Services\VehiclePrerequisiteService;
use Throwable;

class UpsertVehicleLeadMetaUseCase
{
    public function __construct(
        protected LeadMetaService $lead_meta_service,
        protected VehiclePrerequisiteService $vehicle_prerequisite_service
    ) {}

    public function execute(Lead $lead, array $data): void
    {
        try {
            [$make, $model, $trim] = $this->resolveVehicle($data);

            $data = $this->enrichVehicleData($data, $make->name, $model->name, $trim->name);

            $code = LeadProductType::fromValue($lead->insurance_product_code);

            $this->lead_meta_service->updateMeta($lead, $data, $code);
        } catch (Throwable $e) {
            throw new LeadMetaUpsertException($e);
        }
    }

    private function resolveVehicle(array $data): array
    {
        $year = $data['vehicle_year'];
        $makeId = $data['vehicle_make_id'];
        $modelId = $data['vehicle_model_id'];

        return [
            $this->vehicle_prerequisite_service->make($year),
            $this->vehicle_prerequisite_service->model($year, $makeId),
            $this->vehicle_prerequisite_service->trim($year, $makeId, $modelId),
        ];
    }

    private function enrichVehicleData(
        array $data,
        string $make,
        string $model,
        string $trim
    ): array {
        $data['vehicle_make']  = $make;
        $data['vehicle_model'] = $model;
        $data['vehicle_trim']  = $trim;
        $data['lead_details']  = "{$make} {$model} {$trim} - {$data['vehicle_year']}";

        return $data;
    }
}
