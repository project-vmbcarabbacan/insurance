<?php

namespace App\Models;

use App\Shared\Domain\Enums\CoverageType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class HealthPolicy extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'policy_id',
        'coverage_type',
        'hospital_network',
        'max_coverage',
        'pre_existing_conditions',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'coverage_type' => CoverageType::class,
        ];
    }

    /**
     * Get the policy that is assigned to this health.
     */
    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    /**
     * Get all members that are assigned to this health policy.
     *
     * This defines a one-to-many relationship where
     * a single role can be associated with multiple members.
     */
    public function members()
    {
        return $this->hasMany(HealthMember::class);
    }

    /**
     * Get all documents attached to this health policy.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'owner');
    }

    /**
     * Get all AuditLog attached to this health policy.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
