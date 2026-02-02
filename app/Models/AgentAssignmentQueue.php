<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentAssignmentQueue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'agent_id',
        'insurance_product_code',
        'position',
        'last_assigned_at',
        'is_active',
    ];

    protected $casts = [
        'last_assigned_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
