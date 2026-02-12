<?php

namespace App\Modules\Document\Infrastructure\repositories;

use App\Models\DocumentType;
use App\Modules\Document\Domain\Contracts\DocumentTypeRepositoryContract;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use Illuminate\Database\Eloquent\Collection;

class DocumentTypeRepository implements DocumentTypeRepositoryContract
{
    public function getDocumentTypes(LeadProductType $productCode): ?Collection
    {
        return DocumentType::product($productCode)->get();
    }

    public function getDocumentGeneralTypes(LeadProductType $productCode): ?Collection
    {
        return DocumentType::productGeneral($productCode)->get();
    }
}
