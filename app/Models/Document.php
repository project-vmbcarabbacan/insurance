<?php

namespace App\Models;

use App\Shared\Domain\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Document extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'type',
        'file_path',
        'status',
        'uploaded_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => DocumentStatus::class,
        ];
    }

    /**
     * Get the owning model (Customer, Policy, Claim, etc.)
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get all AuditLog attached to this document.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
