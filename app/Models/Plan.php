<?php

namespace App\Models;

use App\Shared\Domain\Enums\Currency;
use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\Enums\QuotationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Plan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'provider_id',
        'insurance_product_code',
        'code',
        'name',
        'description',
        'base_premium',
        'currency',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'currency' => Currency::class,
            'status' => GenericStatus::class,
        ];
    }

    /**
     * Get the policy provider that is assigned to this plan.
     */
    public function provider()
    {
        return $this->belongsTo(PolicyProvider::class);
    }

    /**
     * Get the product that is assigned to this plan.
     */
    public function product()
    {
        return $this->belongsTo(InsuranceProduct::class);
    }

    /**
     * Get all pricing rules that are assigned to this plan.
     *
     * This defines a one-to-many relationship where
     * a single plan can be associated with multiple pricing rules.
     */
    public function users()
    {
        return $this->hasMany(PlanPricingRule::class);
    }

    /**
     * Get all AuditLog attached to this plan.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }


    /**
     * Get the quotations that is assigned to this plan.
     */
    public function quotations()
    {
        return $this->hasMany(Quotation::class)->where('status', '<>', QuotationStatus::REJECTED);
    }

    /**
     * Get the policies that is assigned to this plan.
     */
    public function policies()
    {
        return $this->hasMany(Policy::class);
    }
}
