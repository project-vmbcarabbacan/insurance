<?php

namespace App\Modules\Role\Application\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Modules\Role\Domain\Contracts\PermissionRepositoryContract;
use App\Modules\Role\Domain\Contracts\RolePermissionRepositoryContract;
use App\Modules\Role\Domain\Contracts\RoleRepositoryContract;
use App\Modules\Role\Domain\Entities\RoleEntity;
use App\Modules\Role\Domain\Entities\RolePermissionEntity;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;
use Illuminate\Database\Eloquent\Collection;

class RoleService
{

    public function __construct(
        protected RoleRepositoryContract $role_repository_contract,
        protected PermissionRepositoryContract $permission_repository_contract,
        protected RolePermissionRepositoryContract $role_permission_repository_contract
    ) {}

    /**
     * Retrieve all roles.
     *
     * @return Collection<int, Role>
     */
    public function getAllRoles(): Collection
    {
        return $this->role_repository_contract->getAllRoles();
    }

    /**
     * Retrieve a role by its unique identifier.
     *
     * @param GenericId $roleId
     * @return Role|null
     */
    public function getRoleById(GenericId $roleId): ?Role
    {
        return $this->role_repository_contract->findByRoleId($roleId);
    }

    /**
     * Retrieve a role by its slug.
     *
     * @param LowerText $slug
     * @return Role|null
     */
    public function getRoleBySlug(LowerText $slug): ?Role
    {
        return $this->role_repository_contract->findBySlug($slug);
    }

    /**
     * Create a new role.
     *
     * @param RoleEntity $roleEntity
     * @return void
     */
    public function createRole(RoleEntity $roleEntity): void
    {
        $this->role_repository_contract->createRole($roleEntity);
    }

    /**
     * Update an existing role by ID.
     *
     * @param GenericId  $roleId
     * @param RoleEntity $roleEntity
     * @return void
     */
    public function updateRole(GenericId $roleId, RoleEntity $roleEntity): void
    {
        $this->role_repository_contract->updateRole($roleId, $roleEntity);
    }

    /**
     * Retrieve all permissions.
     *
     * @return Collection<int, Permission>
     */
    public function getAllPermissions(): Collection
    {
        return $this->permission_repository_contract->getAllPermissions();
    }

    /**
     * Retrieve a permission by its unique identifier.
     *
     * @param GenericId $permissionId
     * @return Permission|null
     */
    public function getPermissionById(GenericId $permissionId): ?Permission
    {
        return $this->permission_repository_contract->findById($permissionId);
    }

    /**
     * Retrieve all permissions assigned to a specific role.
     *
     * @param GenericId $roleId
     * @return Collection<int, RolePermission>
     */
    public function getALlPermissionByRoleId(GenericId $roleId): Collection
    {
        return $this->role_permission_repository_contract->findAllPermissionByRoleId($roleId);
    }

    /**
     * Assign a permission to a role.
     *
     * @param RolePermissionEntity $rolePermissionEntity
     * @return void
     */
    public function addRolePermission(RolePermissionEntity $rolePermissionEntity): void
    {
        $this->role_permission_repository_contract->add($rolePermissionEntity);
    }

    /**
     * Revoke a permission from a role.
     *
     * @param GenericId $rolePermissionId
     * @return void
     */
    public function deleteRolePermission(GenericId $rolePermissionId): void
    {
        $this->role_permission_repository_contract->delete($rolePermissionId);
    }
}
