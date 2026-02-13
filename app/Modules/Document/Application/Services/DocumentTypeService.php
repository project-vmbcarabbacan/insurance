<?php

namespace App\Modules\Document\Application\Services;

use App\Modules\Document\Domain\Contracts\DocumentTypeRepositoryContract;
use App\Modules\Lead\Domain\Enums\LeadProductType;

class DocumentTypeService
{

    public function __construct(
        public DocumentTypeRepositoryContract $documentTypeRepositoryContract
    ) {}

    public function getDocumentWithGeneral(LeadProductType $product)
    {
        return $this->documentTypeRepositoryContract->getDocumentGeneralTypes($product);
    }

    public function getDocument(LeadProductType $product)
    {
        return $this->documentTypeRepositoryContract->getDocumentTypes($product);
    }
}
