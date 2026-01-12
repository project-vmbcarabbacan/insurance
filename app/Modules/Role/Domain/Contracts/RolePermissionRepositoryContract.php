<?php

namespace App\Modules\Role\Domain\Contracts;

use App\Modules\Role\Domain\Entities\RolePermissionEntity;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Database\Eloquent\Collection;

interface RolePermissionRepositoryContract
{
    public function findAllPermissionByRoleId(GenericId $roleId): Collection;
    public function add(RolePermissionEntity $rolePermissionEntity): void;
    public function delete(GenericId $rolePermissionId): void;
}
