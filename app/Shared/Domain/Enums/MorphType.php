<?php

namespace App\Shared\Domain\Enums;

enum MorphType: string
{
    case CUSTOMER           = 'customer';
    case POLICY             = 'policy';
    case CLAIM              = 'claim';
    case LEAD               = 'lead';
    case VEHICLE            = 'vehicle';
    case HEALTH             = 'health';
    case TRAVEL             = 'travel';
    case PET                = 'pet';
    case HOME               = 'home';
    case DOCUMENT           = 'document';
    case AUDIT              = 'audit';
    case HEALTH_MEMBER      = 'health_member';
    case LEAD_META          = 'lead_meta';
    case PAYMENT            = 'payment';
    case QUOTATION          = 'quotation';
    case USER               = 'user';
    case ROLE               = 'role';
    case PERMISSION         = 'permission';
    case ROLE_PERMISSION    = 'role_permission';
    case POLICY_PROVIDER    = 'policy_provider';
    case PLAN               = 'plan';
    case PLAN_FEATURE       = 'plan_feature';
    case PLAN_PRICING_RULE  = 'plan_pricing_rule';

    /**
     * Get the fully qualified model class name.
     */
    public function model(): string
    {
        return match ($this) {
            self::CUSTOMER          => \App\Models\Customer::class,
            self::POLICY            => \App\Models\Policy::class,
            self::CLAIM             => \App\Models\Claim::class,
            self::LEAD              => \App\Models\Lead::class,
            self::VEHICLE           => \App\Models\VehiclePolicy::class,
            self::HEALTH            => \App\Models\HealthPolicy::class,
            self::TRAVEL            => \App\Models\TravelPolicy::class,
            self::PET               => \App\Models\PetPolicy::class,
            self::HOME              => \App\Models\HomePolicy::class,
            self::DOCUMENT          => \App\Models\Document::class,
            self::AUDIT             => \App\Models\AuditLog::class,
            self::HEALTH_MEMBER     => \App\Models\HealthMember::class,
            self::LEAD_META         => \App\Models\LeadMeta::class,
            self::PAYMENT           => \App\Models\Payment::class,
            self::QUOTATION         => \App\Models\Quotation::class,
            self::USER              => \App\Models\User::class,
            self::ROLE              => \App\Models\Role::class,
            self::PERMISSION        => \App\Models\Permission::class,
            self::ROLE_PERMISSION   => \App\Models\RolePermission::class,
            self::POLICY_PROVIDER   => \App\Models\PolicyProvider::class,
            self::PLAN              => \App\Models\Plan::class,
            self::PLAN_FEATURE      => \App\Models\PlanFeature::class,
            self::PLAN_PRICING_RULE => \App\Models\PlanPricingRule::class,
        };
    }

    /**
     * Return morph map array for Laravel.
     */
    public static function morphMap(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->model()])
            ->toArray();
    }

    /**
     * Validate allowed morph values (useful for FormRequest).
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
