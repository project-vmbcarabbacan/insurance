<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AgentProductAccess extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'agent_id',
        'insurance_product_code',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeAgentId(Builder $query, int $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    public function scopeCode(Builder $query, string $code)
    {
        return $query->where('insurance_product_code', $code);
    }
}
