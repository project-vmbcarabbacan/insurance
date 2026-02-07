<?php

namespace App\Modules\Lead\Infrastructure\Http\Controllers;

use App\Modules\Customer\Application\Services\CustomerService;
use App\Modules\Lead\Application\UseCases\CreateLeadUseCase;
use App\Modules\Lead\Application\UseCases\UpsertHealthLeadMetaUseCase;
use App\Modules\Lead\Infrastructure\Http\Requests\HealthUpsertRequest;
use Illuminate\Support\Facades\DB;

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
                $lead = $create_lead_usecase->execute($customer, $dto);
                $upsert_lead_meta_usecase->execute($lead, $data);
            });

            return response()->json([
                'message' => 'Health lead upsert'
            ], 201);
        } catch (\Exception $e) {
            \Log::info($e);
        }
    }
}
