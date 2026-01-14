<?php

namespace App\Modules\Quotation\Infrastructure\Repositories;

use App\Models\Quotation;
use App\Modules\Quotation\Domain\Contracts\QuotationRepositoryContract;
use App\Modules\Quotation\Domain\Entities\QuotationEntity;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\ValueObjects\GenericId;

class QuotationRepository implements QuotationRepositoryContract
{
    public function addQuotation(QuotationEntity $quotationEntity): void
    {
        $lead = Quotation::create($quotationEntity->toArray());

        // Record audit log for user creation
        insurance_audit(
            $lead,
            AuditAction::QUOTE_CREATED,
            null,
            ['type' => 'created']
        );
    }

    public function updateQuotation(GenericId $quotationId, QuotationEntity $quotationEntity): void
    {
        $quotation = $this->findById($quotationId);

        /**
         * Extract only non-null values
         */
        $updates = array_non_null_values($quotationEntity->toArray());


        /**
         * No changes — avoid unnecessary DB hit
         */
        if ($updates === []) {
            return;
        }

        /**
         * Capture original values for audit
         */
        $oldValues = array_old_values($quotation, $updates);

        $quotation->update($updates);

        insurance_audit(
            $quotation,
            AuditAction::QUOTE_UPDATED,
            $oldValues,
            $updates
        );
    }

    public function findQuoteByLeadId(GenericId $leadId): ?Quotation
    {
        return Quotation::lead($leadId->value())->latest()->first();
    }

    public function findById(GenericId $quotationId): ?Quotation
    {
        return Quotation::find($quotationId->value());
    }
}
