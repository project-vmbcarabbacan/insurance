<?php

namespace App\Modules\Role\Domain\Entities;

use App\Shared\Domain\ValueObjects\LowerText;

class RoleEntity
{
    public function __construct(
        public readonly LowerText $slug,
        public readonly LowerText $name,
    ) {}

    public function toArray()
    {
        return [
            'slug' => $this->slug->value(),
            'name' => $this->name->value(),
        ];
    }
}
