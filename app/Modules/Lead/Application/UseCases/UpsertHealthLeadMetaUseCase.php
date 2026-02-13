<?php

namespace App\Modules\Lead\Application\UseCases;

use App\Models\Lead;
use App\Modules\Lead\Application\Exceptions\LeadMetaUpsertException;
use App\Modules\Lead\Application\Services\LeadMetaService;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Shared\Application\Services\MasterService;
use App\Shared\Domain\Enums\HealthInsuranceFor;
use App\Shared\Domain\Enums\HealthInsureTo;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;

class UpsertHealthLeadMetaUseCase
{
    public function __construct(
        protected LeadMetaService $leadMetaService,
        protected MasterService $masterService,
    ) {}

    public function execute(Lead $lead, array $data)
    {
        try {
            $code = LeadProductType::fromValue($lead->insurance_product_code);

            $nationality_name = $this->resolveNationalityName($data['nationality'] ?? null);

            $data = $this->enrichHealthData($data, $nationality_name);

            $uuid = Uuid::fromString($lead->uuid);

            $leadMeta = $this->leadMetaService->byLeadId($uuid, $code, ['member_count']);

            /* if member_count does not match with the previous count, Delete all the members */
            if ((int) $leadMeta->member_count <> $data['member_count']) {
                $leadId = GenericId::fromId($lead->id);
                $keys = $this->leadMetaService->memberKeys($leadId, $code);
                $this->leadMetaService->deleteMeta($leadId, $code, $keys);
            }

            $this->leadMetaService->updateMeta($lead, $data, $code);
        } catch (\Exception $e) {
            throw new LeadMetaUpsertException();
        }
    }

    private function resolveNationalityName(?string $nationality): string
    {
        if (empty($nationality)) {
            return '';
        }

        $country = $this->masterService->findCountryByValue($nationality);

        return $country['label'] ?? '';
    }

    private function enrichHealthData(array $data, string $nationality_name): array
    {
        $insuranceFor = HealthInsuranceFor::fromValue($data['insurance_for']);

        $data['lead_details'] = match ($insuranceFor) {
            HealthInsuranceFor::SELF => $this->buildSelfDetails($data),

            HealthInsuranceFor::DOMESTIC_WORKER =>
            HealthInsuranceFor::DOMESTIC_WORKER->label(),

            HealthInsuranceFor::INVESTOR => $this->buildInvestorDetails($data),
        };

        $data['nationality_name'] = $nationality_name;

        return $data;
    }

    private function buildSelfDetails(array $data): string
    {
        return isset($data['insure_to'])
            ? HealthInsureTo::fromValue($data['insure_to'])->label()
            : HealthInsuranceFor::SELF->label();
    }

    private function buildInvestorDetails(array $data): string
    {
        $gender = $data['gender'] ?? '';

        return trim(
            HealthInsuranceFor::INVESTOR->label() . ' ' . ($gender ?? '')
        );
    }
}
