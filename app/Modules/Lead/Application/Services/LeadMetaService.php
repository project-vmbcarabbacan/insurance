<?php

namespace App\Modules\Lead\Application\Services;

use App\Modules\Lead\Application\DTOs\LeadMetaDto;
use App\Modules\Lead\Domain\Contracts\LeadMetaRepositoryContract;
use App\Modules\Lead\Domain\Entities\LeadMetaEntity;

class LeadMetaService
{

    protected function __construct(
        protected LeadMetaRepositoryContract $lead_meta_repository_contract
    ) {}

    public function addMeta(LeadMetaDto $leadMetaDto)
    {
        $entity = new LeadMetaEntity(
            lead_id: $leadMetaDto->lead_id,
            key: $leadMetaDto->key,
            value: $leadMetaDto->value
        );

        $this->lead_meta_repository_contract->addLeadMeta($entity);
    }

    public function updateMeta(LeadMetaEntity $leadMetaDto)
    {
        $entity = new LeadMetaEntity(
            lead_id: $leadMetaDto->lead_id,
            key: $leadMetaDto->key,
            value: $leadMetaDto->value
        );

        $this->lead_meta_repository_contract->updateLeadMeta($entity);
    }
}
