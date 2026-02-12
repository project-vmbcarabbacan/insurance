<?php

namespace App\Shared\Domain\Enums;

/**
 * AuditAction
 *
 * Canonical list of ALL audit log actions.
 * ⚠️ Never rename values once in production.
 */
enum AuditAction: string
{
    /*
    |--------------------------------------------------------------------------
    | Authentication & Security
    |--------------------------------------------------------------------------
    */
    case USER_LOGGED_IN = 'user_logged_in'; // x
    case USER_LOGGED_OUT = 'user_logged_out';
    case USER_LOGIN_FAILED = 'user_login_failed'; // x
    case TOKEN_ISSUED = 'token_issued';
    case TOKEN_REVOKED = 'token_revoked'; // x
    case ALL_TOKENS_REVOKED = 'all_tokens_revoked'; // x
    case PASSWORD_CHANGED = 'password_changed'; // x
    case PASSWORD_RESET_REQUESTED = 'password_reset_requested';
    case PASSWORD_RESET_COMPLETED = 'password_reset_completed';
    case TWO_FACTOR_ENABLED = 'two_factor_enabled';
    case TWO_FACTOR_DISABLED = 'two_factor_disabled';

        /*
    |--------------------------------------------------------------------------
    | User & RBAC
    |--------------------------------------------------------------------------
    */
    case USER_CREATED = 'user_created'; // x
    case USER_UPDATED = 'user_updated';
    case USER_ACTIVATED = 'user_activated'; // x
    case USER_DEACTIVATED = 'user_deactivated';
    case USER_SUSPENDED = 'user_suspended';
    case USER_DELETED = 'user_deleted';

    case ROLE_CREATED = 'role_created'; // x
    case ROLE_UPDATED = 'role_updated';
    case ROLE_DELETED = 'role_deleted';
    case ROLE_ASSIGNED = 'role_assigned'; // x
    case ROLE_REVOKED = 'role_revoked'; // x

    case PERMISSION_CREATED = 'permission_created';
    case PERMISSION_UPDATED = 'permission_updated';
    case PERMISSION_DELETED = 'permission_deleted';
    case PERMISSION_ASSIGNED = 'permission_assigned'; // x
    case PERMISSION_REVOKED = 'permission_revoked'; // x

    case PRODUCT_ASSIGNMENT = 'product_assignment';

        /*
    |--------------------------------------------------------------------------
    | Lead Management
    |--------------------------------------------------------------------------
    */
    case LEAD_CREATED = 'lead_created';
    case LEAD_UPDATED = 'lead_updated';
    case LEAD_STATUS_UPDATED = 'lead_status_updated';
    case LEAD_ASSIGNED = 'lead_assigned';
    case LEAD_REASSIGNED = 'lead_reassigned';
    case LEAD_CONTACTED = 'lead_contacted';
    case LEAD_QUALIFIED = 'lead_qualified';
    case LEAD_UNRESPONSIVE = 'lead_unresponsive';
    case LEAD_LOST = 'lead_lost';
    case LEAD_CONVERTED_TO_CUSTOMER = 'lead_converted_to_customer';
    case LEAD_META_CREATED = 'lEAD_META_CREATED';
    case LEAD_META_UPDATED = 'lEAD_META_UPDATED';

        /*
    |--------------------------------------------------------------------------
    | Quotation
    |--------------------------------------------------------------------------
    */
    case QUOTE_CREATED = 'quote_created';
    case QUOTE_UPDATED = 'quote_updated';
    case QUOTE_PRICING_CALCULATED = 'quote_pricing_calculated';
    case QUOTE_SENT = 'quote_sent';
    case QUOTE_RESENT = 'quote_resent';
    case QUOTE_EXPIRED = 'quote_expired';
    case QUOTE_ACCEPTED = 'quote_accepted';
    case QUOTE_REJECTED = 'quote_rejected';
    case QUOTE_CONVERTED_TO_POLICY = 'quote_converted_to_policy';

        /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */
    case PAYMENT_INITIATED = 'payment_initiated';
    case PAYMENT_PENDING = 'payment_pending';
    case PAYMENT_COMPLETED = 'payment_completed';
    case PAYMENT_FAILED = 'payment_failed';
    case PAYMENT_RETRIED = 'payment_retried';
    case PAYMENT_REFUNDED = 'payment_refunded';
    case PAYMENT_PARTIALLY_REFUNDED = 'payment_partially_refunded';
    case PAYMENT_CANCELLED = 'payment_cancelled';

        /*
    |--------------------------------------------------------------------------
    | Policy Lifecycle
    |--------------------------------------------------------------------------
    */
    case POLICY_DRAFT_CREATED = 'policy_draft_created';
    case POLICY_UPDATED = 'policy_updated';
    case POLICY_NUMBER_GENERATED = 'policy_number_generated';
    case POLICY_ACTIVATED = 'policy_activated';
    case POLICY_CANCELLED = 'policy_cancelled';
    case POLICY_EXPIRED = 'policy_expired';
    case POLICY_SUSPENDED = 'policy_suspended';
    case POLICY_REINSTATED = 'policy_reinstated';
    case POLICY_RENEWAL_INITIATED = 'policy_renewal_initiated';
    case POLICY_RENEWED = 'policy_renewed';
    case POLICY_NON_RENEWED = 'policy_non_renewed';
    case POLICY_ENDORSED = 'policy_endorsed';
    case POLICY_COVERAGE_UPDATED = 'policy_coverage_updated';
    case POLICY_VEHICLE_CREATED = 'policy_vehicle_created';
    case POLICY_VEHICLE_UPDATED = 'policy_vehicle_updated';
    case POLICY_HEALTH_CREATED = 'policy_health_created';
    case POLICY_HEALTH_UPDATED = 'policy_health_updated';
    case POLICY_HEALTH_MEMBER_CREATED = 'policy_health_member_created';
    case POLICY_HEALTH_MEMBER_UPDATED = 'policy_health_member_updated';
    case POLICY_TRAVEL_CREATED = 'policy_travel_created';
    case POLICY_TRAVEL_UPDATED = 'policy_travel_updated';
    case POLICY_HOME_CREATED = 'policy_home_created';
    case POLICY_HOME_UPDATED = 'policy_home_updated';
    case POLICY_PET_CREATED = 'policy_pet_created';
    case POLICY_PET_UPDATED = 'policy_pet_updated';

        /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */
    case DOCUMENT_UPLOADED = 'document_uploaded';
    case DOCUMENT_UPDATED = 'document_updated';
    case DOCUMENT_DELETED = 'document_deleted';
    case DOCUMENT_VERIFIED = 'document_verified';
    case DOCUMENT_REJECTED = 'document_rejected';
    case DOCUMENT_EXPIRED = 'document_expired';
    case DOCUMENT_ARCHIVED = 'document_archived';
    case DOCUMENT_REPLACED = 'document_replaced';
    case DOCUMENT_RENAME = 'document_rename';

        /*
    |--------------------------------------------------------------------------
    | Claims
    |--------------------------------------------------------------------------
    */
    case CLAIM_CREATED = 'claim_created';
    case CLAIM_SUBMITTED = 'claim_submitted';
    case CLAIM_DOCUMENTS_UPLOADED = 'claim_documents_uploaded';
    case CLAIM_REVIEW_STARTED = 'claim_review_started';
    case CLAIM_ADDITIONAL_INFO_REQUESTED = 'claim_additional_info_requested';
    case CLAIM_APPROVED = 'claim_approved';
    case CLAIM_REJECTED = 'claim_rejected';
    case CLAIM_PAID = 'claim_paid';
    case CLAIM_CLOSED = 'claim_closed';

        /*
    |--------------------------------------------------------------------------
    | Customer Lifecycle
    |--------------------------------------------------------------------------
    */
    case CUSTOMER_CREATED = 'customer_created';
    case CUSTOMER_UPDATED = 'customer_updated';
    case CUSTOMER_STATUS_CHANGED = 'customer_status_changed';
    case CUSTOMER_ACTIVATED = 'customer_activated';
    case CUSTOMER_DEACTIVATED = 'customer_deactivated';
    case CUSTOMER_CHURNED = 'customer_churned';
    case CUSTOMER_REACTIVATED = 'customer_reactivated';

        /*
    |--------------------------------------------------------------------------
    | Providers & Plans
    |--------------------------------------------------------------------------
    */
    case PROVIDER_CREATED = 'provider_created';
    case PROVIDER_UPDATED = 'provider_updated';
    case PROVIDER_ACTIVATED = 'provider_activated';
    case PROVIDER_DEACTIVATED = 'provider_deactivated';

    case PLAN_CREATED = 'plan_created';
    case PLAN_UPDATED = 'plan_updated';
    case PLAN_ACTIVATED = 'plan_activated';
    case PLAN_DEACTIVATED = 'plan_deactivated';
    case PLAN_PRICING_UPDATED = 'plan_pricing_updated';
    case PLAN_COVERAGE_UPDATED = 'plan_coverage_updated';

        /*
    |--------------------------------------------------------------------------
    | Underwriting & Risk
    |--------------------------------------------------------------------------
    */
    case UNDERWRITING_STARTED = 'underwriting_started';
    case UNDERWRITING_COMPLETED = 'underwriting_completed';
    case UNDERWRITING_APPROVED = 'underwriting_approved';
    case UNDERWRITING_REJECTED = 'underwriting_rejected';
    case RISK_SCORE_CALCULATED = 'risk_score_calculated';
    case MANUAL_REVIEW_REQUESTED = 'manual_review_requested';
    case MANUAL_REVIEW_COMPLETED = 'manual_review_completed';

        /*
    |--------------------------------------------------------------------------
    | Reports & Exports
    |--------------------------------------------------------------------------
    */
    case REPORT_GENERATED = 'report_generated';
    case REPORT_EXPORTED = 'report_exported';
    case REPORT_DOWNLOADED = 'report_downloaded';

        /*
    |--------------------------------------------------------------------------
    | System & Configuration
    |--------------------------------------------------------------------------
    */
    case SYSTEM_SETTING_UPDATED = 'system_setting_updated';
    case WORKFLOW_RULE_UPDATED = 'workflow_rule_updated';
    case DOCUMENT_REQUIREMENT_UPDATED = 'document_requirement_updated';
    case PRICING_RULE_UPDATED = 'pricing_rule_updated';
    case UTM_RULE_UPDATED = 'utm_rule_updated';

        /*
    |--------------------------------------------------------------------------
    | Notifications & Communication
    |--------------------------------------------------------------------------
    */
    case EMAIL_SENT = 'email_sent';
    case SMS_SENT = 'sms_sent';
    case WHATSAPP_SENT = 'whatsapp_sent';
    case NOTIFICATION_SENT = 'notification_sent';
    case REMINDER_SENT = 'reminder_sent';

        /*
    |--------------------------------------------------------------------------
    | Renewals & Endorsements
    |--------------------------------------------------------------------------
    */
    case RENEWAL_REMINDER_SENT = 'renewal_reminder_sent';
    case RENEWAL_QUOTE_GENERATED = 'renewal_quote_generated';
    case RENEWAL_PAYMENT_COMPLETED = 'renewal_payment_completed';
    case ENDORSEMENT_CREATED = 'endorsement_created';
    case ENDORSEMENT_APPROVED = 'endorsement_approved';
    case ENDORSEMENT_APPLIED = 'endorsement_applied';

        /*
    |--------------------------------------------------------------------------
    | Compliance & Audit
    |--------------------------------------------------------------------------
    */
    case AUDIT_LOG_VIEWED = 'audit_log_viewed';
    case AUDIT_LOG_EXPORTED = 'audit_log_exported';
    case DATA_ACCESSED_SENSITIVE = 'data_accessed_sensitive';
    case DATA_MASKING_APPLIED = 'data_masking_applied';
    case CONSENT_GIVEN = 'consent_given';
    case CONSENT_REVOKED = 'consent_revoked';
}
