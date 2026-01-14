<?php

namespace App\Shared\Domain\Audits;

use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\Enums\PolicyStatus;
use App\Shared\Domain\Exceptions\InvalidPolicyAuditTransitionException;

final class PolicyAuditTransitionMap
{
    public static function map(): array
    {
        return [
            AuditAction::POLICY_DRAFT_CREATED => [
                'from' => null,
                'to' => PolicyStatus::DRAFT
            ],

            AuditAction::POLICY_ACTIVATED => [
                'from' => PolicyStatus::DRAFT,
                'to' => PolicyStatus::ACTIVE,
            ],

            AuditAction::POLICY_SUSPENDED => [
                'from' => PolicyStatus::ACTIVE,
                'to' => PolicyStatus::SUSPENDED,
            ],

            AuditAction::POLICY_REINSTATED => [
                'from' => PolicyStatus::SUSPENDED,
                'to' => PolicyStatus::ACTIVE,
            ],

            AuditAction::POLICY_EXPIRED => [
                'from' => PolicyStatus::ACTIVE,
                'to' => PolicyStatus::EXPIRED,
            ],

            AuditAction::POLICY_CANCELLED => [
                'from' => [
                    PolicyStatus::DRAFT,
                    PolicyStatus::ACTIVE,
                    PolicyStatus::SUSPENDED,
                ],
                'to' => PolicyStatus::CANCELLED,
            ],

            AuditAction::POLICY_RENEWED => [
                'from' => PolicyStatus::ACTIVE,
                'to' => PolicyStatus::RENEWED,
            ],

            AuditAction::POLICY_NON_RENEWED => [
                'from' => PolicyStatus::RENEWED,
                'to' => PolicyStatus::NON_RENEWED,
            ],

            AuditAction::POLICY_ENDORSED => [
                'from' => [
                    PolicyStatus::SUSPENDED,
                    PolicyStatus::CANCELLED,
                    PolicyStatus::EXPIRED,
                    PolicyStatus::NON_RENEWED,
                    PolicyStatus::REINSTATED,
                ],
                'to' => PolicyStatus::ENDORSED,
            ],

            AuditAction::POLICY_RENEWAL_INITIATED => [
                'from' => [
                    PolicyStatus::RENEWED,
                    PolicyStatus::NON_RENEWED,
                ],
                'to' => PolicyStatus::RENEWAL_INITIATED,
            ],

        ];
    }

    /**
     * Validate transition
     */
    public static function validate(
        AuditAction $action,
        ?PolicyStatus $from,
        PolicyStatus $to
    ): void {
        $map = self::map();

        if (! isset($map[$action])) {
            return; // Non-status-changing audits (POLICY_UPDATED, ENDORSED, etc.)
        }

        $allowedFrom = $map[$action]['from'];
        $allowedTo   = $map[$action]['to'];

        $fromValid = is_array($allowedFrom)
            ? in_array($from, $allowedFrom, true)
            : $allowedFrom === $from;

        if (! $fromValid || $allowedTo !== $to) {
            throw new InvalidPolicyAuditTransitionException(
                action: $action->value,
                from: $from?->value,
                to: $to->value
            );
        }
    }
}
