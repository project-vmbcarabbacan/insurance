<?php

namespace App\Modules\Role\Domain\Contracts;

use App\Models\Permission;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Database\Eloquent\Collection;

interface PermissionRepositoryContract
{
    public function findById(GenericId $permissionId): ?Permission;
    public function getAllPermissions(): Collection;
}
