<?php

namespace App\Models;

use App\Shared\Domain\Enums\PropertyType;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class HomePolicy extends Model
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
        'property_type',
        'address',
        'year_built',
        'property_value',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'property_type' => PropertyType::class,
        ];
    }

    /**
     * Get the policy that is assigned to this home.
     */
    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    /**
     * Get all documents attached to this home policy.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'owner');
    }

    /**
     * Get all AuditLog attached to this home policy.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    public function scopePolicy(Builder $query, GenericId $policyId)
    {
        return $query->where('policy_id', $policyId->value());
    }
}
