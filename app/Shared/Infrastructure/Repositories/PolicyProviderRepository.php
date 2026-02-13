<?php

namespace App\Shared\Infrastructure\Repositories;

use App\Models\PolicyProvider;
use App\Shared\Domain\Contracts\PolicyProviderRepositoryContract;
use App\Shared\Domain\Entities\PolicyProviderEntity;
use App\Shared\Domain\Entities\PolicyProviderFilterEntity;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\ValueObjects\GenericId;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PolicyProviderRepository implements PolicyProviderRepositoryContract
{

    public function addPolicyProvider(PolicyProviderEntity $policyProviderEntity): void
    {
        $payload = array_non_null_values($policyProviderEntity->toArray());

        $provider = PolicyProvider::create($payload);

        insurance_audit(
            $provider,
            AuditAction::PROVIDER_CREATED,
            null,
            ['created_at' => Carbon::now()]
        );
    }

    public function updatePolicyProvider(GenericId $policyProviderId, PolicyProviderEntity $policyProviderEntity): void
    {
        $provider = $this->findPolicyProvider($policyProviderId);

        $updates = array_non_null_values($policyProviderEntity->toArray());

        if ($updates === []) {
            return;
        }

        $oldValues = array_old_values($provider, $updates);

        $provider->update($updates);

        insurance_audit(
            $provider,
            AuditAction::PROVIDER_UPDATED,
            $oldValues,
            $updates
        );
    }

    public function findPolicyProvider(GenericId $policyProviderId): ?PolicyProvider
    {
        return PolicyProvider::find($policyProviderId->value());
    }

    public function getAllPolicyProvider(PolicyProviderFilterEntity $entity): ?LengthAwarePaginator
    {
        $query = PolicyProvider::query();

        $query->when($entity->keyword, fn($q, $keyword) => $q->where('name', 'LIKE', "%{$keyword->value()}%"));

        $query->when($entity->status, fn($q, $status) => $q->where('status', $status->value));

        return $query->paginate($entity->per_page);
    }

    public function activateProvider(GenericId $policyProviderId): void
    {
        $provider = $this->findPolicyProvider($policyProviderId);
        $provider->activate();

        insurance_audit(
            $provider,
            AuditAction::PROVIDER_UPDATED,
            ['status' => GenericStatus::INACTIVE],
            ['status' => GenericStatus::ACTIVE]
        );
    }

    public function inactivateProvider(GenericId $policyProviderId): void
    {
        $provider = $this->findPolicyProvider($policyProviderId);
        $provider->inactivate();

        insurance_audit(
            $provider,
            AuditAction::PROVIDER_UPDATED,
            ['status' => GenericStatus::ACTIVE],
            ['status' => GenericStatus::INACTIVE]
        );
    }

    public function activePolicyProvider(): ?Collection
    {
        return PolicyProvider::active()->get();
    }
}
