<?php

namespace App\Modules\Lead\Domain\Contracts;

use App\Modules\Lead\Domain\Entities\LeadMetaEntity;

interface LeadMetaRepositoryContract
{
    public function addLeadMeta(LeadMetaEntity $leadMetaEntity): void;
    public function updateLeadMeta(LeadMetaEntity $leadMetaEntity): void;
}
