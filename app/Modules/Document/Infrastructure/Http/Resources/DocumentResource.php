<?php

namespace App\Modules\Document\Infrastructure\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    private Collection $documentTypes;

    public function __construct($resource, Collection $documentTypes)
    {
        parent::__construct($resource);
        $this->documentTypes = $documentTypes;
    }

    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'original_name' => $this->original_name,
            'url' => asset('storage/' . $this->file_path),
            'document_type_id' => $this->document_type_id,
            'uploaded_by' => !empty($this->document_type_id)
                ? $this->documentType?->name
                : 'System',
            'uploaded_at' => format_fe_date_time($this->created_at),
            'document_types' => DocumentTypeResource::collection($this->documentTypes)
        ];
    }
}
