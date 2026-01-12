<?php

namespace App\Models;

use App\Shared\Domain\Enums\LeadActivityType;
use Illuminate\Database\Eloquent\Model;

class LeadActivity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lead_id',
        'type',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => LeadActivityType::class,
        ];
    }

    public function scopeLead($query, $leadId)
    {
        return $query->where('lead_id', $leadId);
    }
}
