<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class HealthMember extends Model
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
        'health_policy_id ',
        'first_name',
        'family_name',
        'dob',
        'relationship',
    ];


    /**
     * Get the health policy that is assigned to this member.
     */
    public function healthPolicy()
    {
        return $this->belongsTo(HealthPolicy::class);
    }

    /**
     * Get all AuditLog attached to this health member.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
