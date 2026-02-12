<?php

namespace App\Models;

use App\Shared\Domain\Enums\DocumentStatus;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Database\Eloquent\Builder;
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
        'uuid',
        'lead_id',
        'original_name',
        'mime_type',
        'file_path',
        'status',
        'size',
        'uploaded_by',
        'document_type_id'
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

    public function scopeLead(Builder $query, GenericId $leadId)
    {
        return $query->where('lead_id', $leadId->value());
    }

    public function scopeUuid(Builder $query, Uuid $uuid)
    {
        return $query->where('uuid', $uuid->value());
    }

    public function scopeUploaded(Builder $query)
    {
        return $query->whereIn('status', [DocumentStatus::PENDING->value, DocumentStatus::VERIFIED->value]);
    }

    public function scopeRejected(Builder $query)
    {
        return $query->where('status', DocumentStatus::REJECTED->value);
    }

    public function scopeArchived(Builder $query)
    {
        return $query->where('status', DocumentStatus::ARCHIVED->value);
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

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id', 'id');
    }
}
