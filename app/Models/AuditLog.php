<?php

namespace App\Models;

use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\Enums\MorphType;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'old_values',
        'new_values',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'action' => AuditAction::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeMorphType(Builder $query, MorphType $morph)
    {
        return $query->where('auditable_type', $morph->value);
    }

    public function scopeMorphId(Builder $query, GenericId $id)
    {
        return $query->where('auditable_id', $id->value());
    }

    /**
     * Get the owning model (Customer, Policy, Claim, etc.)
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }
}
