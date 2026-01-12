<?php

namespace App\Modules\Role\Infrastructure\Repositories;

use App\Models\RolePermission;
use App\Modules\Role\Domain\Contracts\RolePermissionRepositoryContract;
use App\Modules\Role\Domain\Entities\RolePermissionEntity;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\ValueObjects\GenericId;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class RolePermissionRepository implements RolePermissionRepositoryContract
{

    /**
     * Retrieve all permissions assigned to a given role.
     *
     * NOTE:
     * This method returns infrastructure models intentionally.
     * Mapping to domain entities should happen at the Application layer if needed.
     *
     * @param GenericId $roleId
     * @return Collection<int, RolePermission>
     */
    public function findAllPermissionByRoleId(GenericId $roleId): Collection
    {
        return RolePermission::role($roleId->value())->get();
    }

    /**
     * Assign a permission to a role.
     *
     * Persists the role-permission relation and records an audit log
     * describing the assignment action.
     *
     * @param RolePermissionEntity $rolePermissionEntity
     */
    public function add(RolePermissionEntity $rolePermissionEntity): void
    {
        $permission = RolePermission::create($rolePermissionEntity->toArray());

        // Record audit log for user creation
        insuranceAudit(
            $permission,
            AuditAction::PERMISSION_ASSIGNED,
            null,
            ['type' => 'assigned']
        );
    }

    /**
     * Revoke a permission from a role.
     *
     * The audit log is recorded BEFORE deletion to ensure
     * the audited model still exists.
     *
     * @param GenericId $rolePermissionId
     */
    public function delete(GenericId $rolePermissionId): void
    {
        $permission = RolePermission::find($rolePermissionId->value())->delete();

        insuranceAudit(
            $permission,
            AuditAction::PERMISSION_REVOKED,
            ['status' => 'assigned'],
            ['type' => 'revoked']
        );
    }
}
