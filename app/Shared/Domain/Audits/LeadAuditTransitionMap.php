<?php

namespace App\Shared\Domain\Audits;

use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\Exceptions\InvalidLeadAuditTransitionException;

final class LeadAuditTransitionMap
{
    /**
     * Lead audit → status transition mapping
     */
    public static function map(): array
    {
        return [
            AuditAction::LEAD_ASSIGNED => [
                'from' => null,
                'to'   => LeadStatus::NEW,
            ],

            AuditAction::LEAD_REASSIGNED => [
                'from' => [
                    LeadStatus::NEW,
                    LeadStatus::CONTACTED,
                    LeadStatus::QUALIFIED,
                    LeadStatus::UNRESPONSIVE,
                ],
                'to' => null, // reassignment does NOT change status
            ],

            AuditAction::LEAD_CONTACTED => [
                'from' => LeadStatus::NEW,
                'to'   => LeadStatus::CONTACTED,
            ],

            AuditAction::LEAD_QUALIFIED => [
                'from' => [
                    LeadStatus::CONTACTED,
                    LeadStatus::UNRESPONSIVE,
                ],
                'to' => LeadStatus::QUALIFIED,
            ],

            AuditAction::LEAD_UNRESPONSIVE => [
                'from' => [
                    LeadStatus::NEW,
                    LeadStatus::CONTACTED,
                ],
                'to' => LeadStatus::UNRESPONSIVE,
            ],

            AuditAction::LEAD_LOST => [
                'from' => [
                    LeadStatus::NEW,
                    LeadStatus::CONTACTED,
                    LeadStatus::QUALIFIED,
                    LeadStatus::NEGOTIATING,
                    LeadStatus::PENDING_PAYMENT,
                ],
                'to' => LeadStatus::LOST,
            ],

            AuditAction::LEAD_CONVERTED_TO_CUSTOMER => [
                'from' => [
                    LeadStatus::QUALIFIED,
                    LeadStatus::PENDING_PAYMENT,
                ],
                'to' => LeadStatus::CONVERTED,
            ],
        ];
    }

    /**
     * Validate lead status transition based on audit action
     */
    public static function validate(
        AuditAction $action,
        ?LeadStatus $from,
        ?LeadStatus $to
    ): void {
        $map = self::map();

        if (! isset($map[$action])) {
            return; // Non-status-changing audits
        }

        $allowedFrom = $map[$action]['from'];
        $allowedTo   = $map[$action]['to'];

        // Validate FROM
        $fromValid = $allowedFrom === null
            ? $from === null
            : (is_array($allowedFrom)
                ? in_array($from, $allowedFrom, true)
                : $allowedFrom === $from);

        // Validate TO (null = no status mutation)
        $toValid = $allowedTo === null || $allowedTo === $to;

        if (! $fromValid || ! $toValid) {
            throw new InvalidLeadAuditTransitionException(
                action: $action->value,
                from: $from?->value,
                to: $to?->value
            );
        }
    }
}
