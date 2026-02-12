<?php

namespace App\Modules\Document\Domain\Contracts;

use App\Modules\Lead\Domain\Enums\LeadProductType;
use Illuminate\Database\Eloquent\Collection;

interface DocumentTypeRepositoryContract
{
    public function getDocumentTypes(LeadProductType $productCode): ?Collection;
    public function getDocumentGeneralTypes(LeadProductType $productCode): ?Collection;
}
