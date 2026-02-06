<?php

namespace App\Models;

use App\Shared\Domain\Enums\CustomerSource;
use App\Shared\Domain\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Lead extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'insurance_product_code',
        'source',
        'status',
        'assigned_agent_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'id',
    ];

    /**
     * Scope to filter by uuid
     */
    public function scopeUuid($query, $uuid)
    {
        return $query->where('uuid', $uuid);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'source' => CustomerSource::class,
            'status' => LeadStatus::class,
        ];
    }

    /**
     * Get all documents attached to this lead.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'owner');
    }

    /**
     * Get all AuditLog attached to this lead.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    public function metas()
    {
        return $this->hasMany(LeadMeta::class);
    }

    public function getMetaObjectAttribute(): array
    {
        return $this->meta
            ->pluck('value', 'key')
            ->toArray();
    }
}
