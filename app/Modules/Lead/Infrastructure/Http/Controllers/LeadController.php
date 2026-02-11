<?php

namespace App\Modules\Lead\Infrastructure\Http\Controllers;

use App\Modules\Customer\Infrastructure\Http\Requests\UuidCustomerRequest;
use App\Modules\Lead\Application\DTOs\LeadActivityDto;
use App\Modules\Lead\Application\Exceptions\LeadUuidNotFoundException;
use App\Modules\Lead\Application\Services\LeadActivityService;
use App\Modules\Lead\Application\Services\LeadService;
use App\Modules\Lead\Application\UseCases\AddLeadActivityUseCase;
use App\Modules\Lead\Domain\Maps\LeadActivityTypeMap;
use App\Modules\Lead\Infrastructure\Http\Requests\LeadActivityRequest;
use App\Modules\Lead\Infrastructure\Http\Requests\UuidCustomerLeadsRequest;
use App\Modules\Lead\Infrastructure\Http\Requests\UuidLeadRequest;
use App\Modules\Lead\Infrastructure\Http\Resources\LeadActivityResource;
use App\Modules\Lead\Infrastructure\Http\Resources\LeadDetailResource;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;
use Illuminate\Support\Facades\DB;

class LeadController
{
    public function addLeadActivity(LeadActivityRequest $request, LeadService $leadService, AddLeadActivityUseCase $addLeadActivityUseCase)
    {
        DB::transaction(function () use ($request, $leadService, $addLeadActivityUseCase) {
            //
            $lead = $leadService->getLeadByUuid($request->uuid());
            if (!$lead) throw new LeadUuidNotFoundException();

            $type = LeadActivityTypeMap::transition($request->leadActivityResponse());

            $dto = new LeadActivityDto(
                lead_id: GenericId::fromId($lead->id),
                type: $type,
                performed_by_name: LowerText::fromString(getName()),
                notes: $request->notes(),
                performed_by_id: GenericId::fromId(getId())
            );

            $addLeadActivityUseCase->execute($request->uuid(), $dto, $request->leadActivityResponse());
        });

        return response()->json([
            'message' => 'Lead activity added'
        ], 201);
    }

    public function leads(UuidCustomerLeadsRequest $request, LeadService $leadService)
    {
        $leads = $leadService->getLeadsByCustomerId(
            $request->customerId(),
            $request->keyword(),
            $request->per_page()
        );

        $leads->through(fn($lead) => new LeadDetailResource($lead));

        return response()->json([
            'message' => 'leads by customer id',
            'data' => [
                'leads' => $leads,
            ]
        ]);
    }

    public function getLeadActivity(UuidLeadRequest $request, LeadService $leadService, LeadActivityService $leadActivityService)
    {
        $lead = $leadService->getLeadByUuid($request->uuid());

        if (!$lead) throw new LeadUuidNotFoundException();

        $activities = $leadActivityService->getAllActivityByLeadId(GenericId::fromId($lead->id));

        return response()->json([
            'message' => 'Lead activity',
            'data' => [
                'activities' => LeadActivityResource::collection($activities)
            ]
        ]);
    }
}
