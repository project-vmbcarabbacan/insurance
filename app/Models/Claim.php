<?php

namespace App\Models;

use App\Shared\Domain\Enums\ClaimStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Claim extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'claim_number',
        'policy_id',
        'incident_date',
        'description',
        'status',
        'claim_amount',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ClaimStatus::class,
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
     * Get all documents attached to this customer.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'owner');
    }
}
