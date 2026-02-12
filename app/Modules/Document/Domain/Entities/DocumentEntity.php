<?php

namespace App\Modules\Document\Domain\Entities;

use App\Models\Document;
use App\Shared\Domain\ValueObjects\GenericId;

class DocumentEntity
{
    public function __construct(
        public readonly GenericId $lead_id,
        public readonly string $originalName,
        public readonly string $path,
        public readonly string $mimeType,
        public readonly int $size,
    ) {}

    public function toArray()
    {
        return [
            'uuid'          => generate_unique_uuid(Document::class),
            'lead_id'       => $this->lead_id->value(),
            'original_name' => $this->originalName,
            'file_path'     => $this->path,
            'mime_type'     => $this->mimeType,
            'size'          => $this->size,
            'uploaded_by'   => getId()
        ];
    }
}
