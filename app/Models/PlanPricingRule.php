<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PlanPricingRule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'plan_id',
        'rule_type',
        'rule_value',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rule_value' => 'array',
        ];
    }

    /**
     * Get the plan that is assigned to this pricing rule.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get all AuditLog attached to this plan pricing rule.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
