<?php

namespace App\Shared\Infrastructure\Http\Controllers;

use App\Shared\Application\Services\PolicyProviderService;
use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Infrastructure\Http\Requests\PolicyProviderFilterRequest;
use App\Shared\Infrastructure\Http\Requests\PolicyProviderRequest;
use App\Shared\Infrastructure\Http\Requests\PolicyProviderStatusRequest;
use App\Shared\Infrastructure\Http\Requests\UuidPolicyProviderRequest;
use App\Shared\Infrastructure\Http\Resources\PolicyProviderLabelValueResource;
use App\Shared\Infrastructure\Http\Resources\PolicyProviderResource;
use Illuminate\Http\Request;

class PolicyProviderController
{

    public function __construct(
        protected PolicyProviderService $policyProviderService
    ) {}

    public function paginateIndex(PolicyProviderFilterRequest $request)
    {
        $providers = $this->policyProviderService->paginated($request->toDto());
        $providers->through(fn($provider) => new PolicyProviderResource($provider));
        $statuses = array_map(
            fn(GenericStatus $case) => [
                'label' => ucwords(strtolower(str_replace('_', ' ', $case->value))),
                'value' => $case->value,
            ],
            [
                GenericStatus::ACTIVE,
                GenericStatus::INACTIVE
            ]
        );

        return response()->json([
            'message' => 'Policy provider listing',
            'data' => [
                'policy_providers' => $providers,
                'statuses' => $statuses
            ]
        ]);
    }

    public function upsert(PolicyProviderRequest $request)
    {
        $this->policyProviderService->upsertPolicyProvider($request->toDto());

        return response()->json([
            'message' => 'Upsert policy provider'
        ]);
    }

    public function search(UuidPolicyProviderRequest $request)
    {
        $search = $this->policyProviderService->search(GenericId::fromId($request->policy_provider_id));

        return response()->json([
            'message' => 'Policy provider',
            'data' => [
                'policy_provider' => new PolicyProviderResource($search)
            ]
        ]);
    }

    public function statusUpdate(PolicyProviderStatusRequest $request)
    {
        $status = GenericStatus::fromValue($request->status);

        $policyProviderId = GenericId::fromId($request->policy_provider_id);
        match ($status) {
            GenericStatus::ACTIVE => $this->policyProviderService->activate($policyProviderId),
            GenericStatus::INACTIVE => $this->policyProviderService->inactivate($policyProviderId),
            default => null
        };

        return response()->json([
            'message' => 'Policy provider status update'
        ]);
    }

    public function active(Request $request)
    {
        $active = $this->policyProviderService->activePolicy();

        return response()->json([
            'message' => 'Policy provider label value',
            'data' => [
                'policy_providers' => PolicyProviderLabelValueResource::collection($active)
            ]
        ]);
    }
}
