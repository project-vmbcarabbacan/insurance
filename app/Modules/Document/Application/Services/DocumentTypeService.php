<?php

namespace App\Modules\Document\Application\Services;

use App\Modules\Document\Domain\Contracts\DocumentTypeRepositoryContract;
use App\Modules\Lead\Domain\Enums\LeadProductType;

class DocumentTypeService
{

    public function __construct(
        public DocumentTypeRepositoryContract $document_type_repository_contract
    ) {}

    public function getDocumentWithGeneral(LeadProductType $product)
    {
        return $this->document_type_repository_contract->getDocumentGeneralTypes($product);
    }

    public function getDocument(LeadProductType $product)
    {
        return $this->document_type_repository_contract->getDocumentTypes($product);
    }
}
