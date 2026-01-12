<?php

namespace App\Models;

use App\Shared\Domain\Enums\Currency;
use App\Shared\Domain\Enums\PolicyStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Policy extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'policy_number',
        'lead_id',
        'customer_id',
        'insurance_product_code',
        'quotation_id',
        'status',
        'start_date',
        'end_date',
        'premium_amount',
        'vat',
        'currency',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PolicyStatus::class,
            'currency' => Currency::class,
        ];
    }

    /**
     * Get the vehicle that is assigned to this policy.
     */
    public function vehicle()
    {
        return $this->belongsTo(VehiclePolicy::class);
    }

    /**
     * Get the policy that is assigned to this health.
     */
    public function health()
    {
        return $this->belongsTo(HealthPolicy::class);
    }

    /**
     * Get the policy that is assigned to this travel.
     */
    public function travel()
    {
        return $this->belongsTo(TravelPolicy::class);
    }

    /**
     * Get the policy that is assigned to this home.
     */
    public function home()
    {
        return $this->belongsTo(HomePolicy::class);
    }

    /**
     * Get the policy that is assigned to this pet.
     */
    public function pet()
    {
        return $this->belongsTo(PetPolicy::class);
    }

    /**
     * Get the product that is assigned to this policy.
     */
    public function product()
    {
        return $this->belongsTo(InsuranceProduct::class);
    }

    /**
     * Get all documents attached to this customer.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'owner');
    }

    /**
     * Get all AuditLog attached to this policy.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    /**
     * Get the provider that is assigned to this policy.
     */
    public function provider()
    {
        return $this->belongsTo(PolicyProvider::class);
    }

    /**
     * Get the plan that is assigned to this policy.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
