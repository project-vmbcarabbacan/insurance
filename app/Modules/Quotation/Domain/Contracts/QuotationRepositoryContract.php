<?php

namespace App\Modules\Quotation\Domain\Contracts;

use App\Models\Quotation;
use App\Modules\Quotation\Domain\Entities\QuotationEntity;
use App\Shared\Domain\ValueObjects\GenericId;

interface QuotationRepositoryContract
{
    public function addQuotation(QuotationEntity $quotationEntity): void;
    public function updateQuotation(GenericId $quotationId, QuotationEntity $quotationEntity): void;
    public function findById(GenericId $quotationId): ?Quotation;
    public function findQuoteByLeadId(GenericId $leadId): ?Quotation;
}
