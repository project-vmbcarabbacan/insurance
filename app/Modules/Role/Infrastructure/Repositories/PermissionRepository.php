<?php

namespace App\Modules\Role\Infrastructure\Repositories;

use App\Models\Permission;
use App\Modules\Role\Domain\Contracts\PermissionRepositoryContract;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository implements PermissionRepositoryContract
{
    /**
     * Retrieve a permission by its unique identifier.
     *
     * This method returns the infrastructure (Eloquent) model.
     * Mapping to a domain entity should be handled at the
     * Application layer if strict domain isolation is required.
     *
     * @param GenericId $permissionId
     * @return Permission|null
     */
    public function findById(GenericId $permissionId): ?Permission
    {
        return Permission::find($permissionId->value());
    }

    /**
     * Retrieve all permissions.
     *
     * This method returns a collection of Permission models.
     * If strict domain isolation is required, mapping to
     * domain entities should be handled at the Application layer.
     *
     * @return Collection<int, Permission>
     */
    public function getAllPermissions(): Collection
    {
        return Permission::query()->get();
    }
}
