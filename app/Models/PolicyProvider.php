<?php

namespace App\Models;

use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\Enums\QuotationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PolicyProvider extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
        'contact_email',
        'contact_phone',
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
            'status' => GenericStatus::class
        ];
    }

    public function activate(): bool
    {
        return $this->update(['status' => GenericStatus::ACTIVE->value]);
    }

    public function inactivate(): bool
    {
        return $this->update(['status' => GenericStatus::INACTIVE->value]);
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', GenericStatus::ACTIVE->value);
    }

    /**
     * Get all plans that are assigned to this policy provider.
     *
     * This defines a one-to-many relationship where
     * a single provider can be associated with multiple plans.
     */
    public function plans()
    {
        return $this->hasMany(Plan::class)->where('status', GenericStatus::ACTIVE);
    }

    /**
     * Get all AuditLog attached to this policy providder.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    /**
     * Get the quotations that is assigned to this provider.
     */
    public function quotations()
    {
        return $this->hasMany(Quotation::class)->where('status', '<>', QuotationStatus::REJECTED);
    }

    /**
     * Get the policies that is assigned to this provider.
     */
    public function policies()
    {
        return $this->hasMany(Policy::class);
    }
}
