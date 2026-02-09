<?php

namespace App\Modules\Lead\Infrastructure\Http\Controllers;

use App\Modules\Customer\Application\Services\CustomerService;
use App\Modules\Lead\Application\UseCases\CreateLeadUseCase;
use App\Modules\Lead\Application\UseCases\LeadByUuidUseCase;
use App\Modules\Lead\Application\UseCases\UpsertVehicleLeadMetaUseCase;
use App\Modules\Lead\Domain\Maps\LeadKeyMap;
use App\Modules\Lead\Domain\Maps\LeadViewMap;
use App\Modules\Lead\Infrastructure\Http\Requests\UuidLeadRequest;
use App\Modules\Lead\Infrastructure\Http\Requests\VehicleUpsertRequest;
use App\Modules\Lead\Infrastructure\Http\Resources\VehicleLeadUpdateResource;
use App\Modules\Lead\Infrastructure\Http\Resources\VehicleLeadVIewResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleLeadController
{
    public function store(
        VehicleUpsertRequest $request,
        CustomerService $customer_service,
        CreateLeadUseCase $create_lead_usecase,
        UpsertVehicleLeadMetaUseCase $upsert_lead_meta_usecase
    ) {
        try {
            DB::transaction(function () use (
                $request,
                $customer_service,
                $create_lead_usecase,
                $upsert_lead_meta_usecase,
            ) {
                $dto = $request->leadDto();
                $data = $request->arrayData();

                $customer = $customer_service->getModelById($request->customerId());
                $lead = $create_lead_usecase->execute($customer, $dto, $request->activeLeadCondition());
                $upsert_lead_meta_usecase->execute($lead, $data);
            });

            return response()->json([
                'message' => 'Vehicle lead upsert'
            ], 201);
        } catch (\Exception $e) {
            Log::info($e);
        }
    }

    public function view(
        UuidLeadRequest $request,
        LeadByUuidUseCase $leadByUuidUseCase
    ) {
        $lead = $leadByUuidUseCase->execute($request->uuid(), LeadKeyMap::viewVehicleLeadById());

        return response()->json([
            'message' => 'View vehicle lead',
            'data' => [
                'lead' => new VehicleLeadVIewResource($lead),
                'view' => LeadViewMap::VehicleView()
            ]
        ]);
    }

    public function find(
        UuidLeadRequest $request,
        LeadByUuidUseCase $leadByUuidUseCase
    ) {
        $lead = $leadByUuidUseCase->execute($request->uuid(), LeadKeyMap::updateVehicleByLeadId());

        return response()->json([
            'message' => 'Find vehicle lead',
            'data' => [
                'lead' => new VehicleLeadUpdateResource($lead),
            ]
        ]);
    }
}
