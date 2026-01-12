<?php

namespace App\Modules\Role\Domain\Entities;

use App\Shared\Domain\ValueObjects\GenericId;

class RolePermissionEntity
{
    public function __construct(
        public readonly GenericId $role_id,
        public readonly GenericId $permission_id,
    ) {}

    public function toArray()
    {
        return [
            'role_id' => $this->role_id->value(),
            'permission_id' => $this->permission_id->value(),
        ];
    }
}
