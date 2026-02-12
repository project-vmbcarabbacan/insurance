<?php

namespace App\Modules\Document\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentTypeResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'value' => $this->id,
            'label' => $this->name,
            'required' => (bool) $this->required
        ];
    }
}
