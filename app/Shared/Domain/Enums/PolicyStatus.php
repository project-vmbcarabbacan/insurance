<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

enum PolicyStatus: string
{
    /**
     * WHEN
     *  - Policy is being created
     *  - Quotation exists but no payment / no issuance
     *  - Incomplete data
     *
     * Changes to
     *  - ACTIVE → once payment is successful & policy is issued
     *  - CANCELLED → draft is abandoned or explicitly cancelled
     *
     */
    case DRAFT = 'draft';

    /**
     * WHEN
     *  - Policy is issued
     *  - Payment completed
     *  - Coverage start date reached
     *
     * Changes to
     *  - SUSPENDED → non-payment, compliance issue
     *  - EXPIRED → end date reached without renewal
     *  - CANCELLED → customer or insurer cancels
     *  - ENDORSED → endorsement applied
     *  - COVERAGE_UPDATED → coverage limits/benefits changed
     *  - RENEWAL_INITIATED → renewal process started
     */
    case ACTIVE = 'active';

    /**
     * WHEN
     *  - Missed payment
     *  - Regulatory or underwriting issue
     *  - Temporary stop of coverage
     *
     * Changes to
     *  - REINSTATED → issue resolved (payment made)
     *  - CANCELLED → suspension period exceeded
     *  - EXPIRED → term ends while suspended
     */
    case SUSPENDED = 'suspended';

    /**
     * WHEN
     *  - Suspended policy is restored
     *  - Outstanding obligations fulfilled
     *
     * Changes to
     *  - ACTIVE → immediately after reinstatement
     */
    case REINSTATED = 'reinstated';

    /**
     * WHEN
     *  - Policy is approaching expiration
     *  - Renewal offer generated
     *  - Awaiting customer action/payment
     *
     * Changes to
     *  - RENEWED → renewal completed
     *  - NON_RENEWED → customer declines / no response
     *  - EXPIRED → renewal window passes
     */
    case RENEWAL_INITIATED = 'renewal_initiated';

    /**
     * WHEN
     *  - Renewal payment completed
     *  - New policy term created
     *
     * Changes to
     *  - ACTIVE → new term becomes active
     */
    case RENEWED = 'renewed';

    /**
     * WHEN
     *  - Customer explicitly declines renewal
     *  - Underwriting rejects renewal
     *
     * Changes to
     *  - EXPIRED → at end of current term
     */
    case NON_RENEWED = 'non_renewed';

    /**
     * WHEN
     *  - Policy amendment (address, insured object, beneficiaries)
     *  - Does not change coverage amount
     *
     * Changes to
     *  - ACTIVE → after endorsement is finalized
     */
    case ENDORSED = 'endorsed';

    /**
     * WHEN
     *  - Coverage limits changed
     *  - Add/remove riders
     *  - Premium recalculated
     *
     * Changes to
     *  - ACTIVE → once update is applied
     */
    case COVERAGE_UPDATED = 'coverage_updated';

    /**
     * END STATE
     */

        /**
     * WHEN
     *  - Policy end date reached
     *  - No successful renewal
     */
    case EXPIRED = 'expired';

    /**
     * WHEN
     *  - Customer cancellation
     *  - Insurer cancellation
     *  - Fraud or underwriting failure
     */
    case CANCELLED = 'cancelled';

    /**
     * Validate and return PolicyStatus enum from string value.
     *
     * @param string $value
     * @return self
     *
     * @throws InvalidValueException
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom(strtolower($value))
            ?? throw InvalidValueException::withMessage("Invalid policy status value {$value}");
    }

    /**
     * Check if the given value exists in the enum.
     *
     * @param string $value
     * @return bool
     */
    public static function exists(string $value): bool
    {
        return self::tryFrom(strtolower($value)) !== null;
    }

    /**
     * Return enum as array of objects for dropdowns
     * [
     *   ['label' => 'Draft', 'value' => 'draft'],
     *   ['label' => 'Active', 'value' => 'active'],
     *   ['label' => 'Expired', 'value' => 'expired'],
     *   ['label' => 'cancelled', 'value' => 'cancelled']
     *   ...
     * ]
     */
    public static function toDropdownArray(): array
    {
        return array_map(
            fn(self $case) => [
                'label' => ucwords(strtolower(str_replace('_', ' ', $case->value))),
                'value' => strtolower($case->value)
            ],
            self::cases()
        );
    }

    /**
     * Convert enum to array for dropdowns / mapping
     * [
     *   'Draft' => 'draft',
     *   'Active' => 'active',
     *   'Expired' => 'expired',
     *   'Cancelled' => 'cancelled'
     *   ...
     * ]
     */
    public static function toLabelArray(): array
    {
        $result = [];

        foreach (self::cases() as $case) {
            // replace underscores with spaces and capitalize each word
            $label = ucwords(strtolower(str_replace('_', ' ', $case->value)));
            $result[$case->value] = $label;
        }

        return $result;
    }
}
