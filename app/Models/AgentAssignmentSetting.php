<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AgentAssignmentSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'insurance_product_code',
        'strategy',
        'max_active_leads_per_agent',
        'reassignment_timeout_minutes',
        'is_active',
    ];

    public function scopeCode(Builder $query, string $code)
    {
        return $query->where('insurance_product_code', $code);
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', 1);
    }
}
