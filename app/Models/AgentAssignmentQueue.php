<?php

namespace App\Models;

use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeCode(Builder $query, string $code)
    {
        return $query->where('insurance_product_code', $code);
    }

    public function scopeAgent(Builder $query, GenericId $agentId)
    {
        return $query->where('agent_id', $agentId->value());
    }

    public function assignments()
    {
        return $this->hasMany(
            AgentAssignment::class,
            'agent_id',
            'agent_id'
        );
    }
}
