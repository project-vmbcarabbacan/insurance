<?php

namespace App\Models;

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
}
