<?php

namespace App\Models;

use App\Shared\Domain\Enums\VehicleType;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class VehiclePolicy extends Model
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
        'vehicle_type',
        'vehicle_make',
        'vehicle_model',
        'year',
        'identifier_type',
        'plate_number',
        'engine_number',
        'vehicle_value'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'vehicle_type' => VehicleType::class,
        ];
    }

    /**
     * Get the policy that is assigned to this vehicle.
     */
    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    /**
     * Get all documents attached to this customer.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'owner');
    }

    /**
     * Get all AuditLog attached to this vehicle policy.
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
