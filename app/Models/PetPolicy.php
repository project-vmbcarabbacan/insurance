<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PetPolicy extends Model
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
        'pet_name',
        'species',
        'breed',
        'age',
        'microchip_number',
    ];

    /**
     * Get the policy that is assigned to this pet.
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
     * Get all AuditLog attached to this pet policy.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
