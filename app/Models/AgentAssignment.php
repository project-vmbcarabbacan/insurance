<?php

namespace App\Models;

use App\Shared\Domain\Enums\AssignmentStatus;
use Illuminate\Database\Eloquent\Model;

class AgentAssignment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lead_id',
        'insurance_product_code',
        'agent_id',
        'status',
        'assigned_at',
        'contacted_at',
        'closed_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'contacted_at' => 'datetime',
        'closed_at' => 'datetime',
        'status' => AssignmentStatus::class
    ];
}
