<?php

namespace App\Modules\Lead\Infrastructure\Http\Controllers;

use App\Modules\Customer\Application\Services\CustomerService;
use App\Modules\Lead\Application\Services\LeadMetaService;
use App\Modules\Lead\Application\UseCases\CreateLeadUseCase;
use App\Modules\Lead\Application\UseCases\LeadByUuidUseCase;
use App\Modules\Lead\Application\UseCases\UpsertHealthLeadMetaUseCase;
use App\Modules\Lead\Domain\Maps\LeadKeyMap;
use App\Modules\Lead\Domain\Maps\LeadViewMap;
use App\Modules\Lead\Infrastructure\Http\Requests\HealthUpsertRequest;
use App\Modules\Lead\Infrastructure\Http\Requests\UuidLeadRequest;
use App\Modules\Lead\Infrastructure\Http\Resources\HealthLeadUpdateResource;
use App\Modules\Lead\Infrastructure\Http\Resources\HealthLeadViewResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HealthLeadController
{
    public function store(
        HealthUpsertRequest $request,
        CustomerService $customer_service,
        CreateLeadUseCase $create_lead_usecase,
        UpsertHealthLeadMetaUseCase $upsert_lead_meta_usecase
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
                'message' => 'Health lead upsert'
            ], 201);
        } catch (\Exception $e) {
            Log::info($e);
        }
    }

    public function view(
        UuidLeadRequest $request,
        LeadByUuidUseCase $leadByUuidUseCase,
        LeadMetaService $leadMetaService
    ) {
        $lead = $leadByUuidUseCase->execute($request->uuid(), LeadKeyMap::viewHealthLeadById());

        return response()->json([
            'message' => 'View health lead',
            'data' => [
                'lead' => new HealthLeadViewResource($lead, $leadMetaService),
                'view' => LeadViewMap::HealthView()
            ]
        ]);
    }

    public function find(
        UuidLeadRequest $request,
        LeadByUuidUseCase $leadByUuidUseCase,
        LeadMetaService $leadMetaService
    ) {
        $lead = $leadByUuidUseCase->execute($request->uuid(), LeadKeyMap::updateHealthByLeadId());

        return response()->json([
            'message' => 'Find health lead',
            'data' => [
                'lead' => new HealthLeadUpdateResource($lead, $leadMetaService),
            ]
        ]);
    }
}
