<?php

namespace App\Modules\Role\Infrastructure\Repositories;

use App\Models\Role;
use App\Modules\Role\Application\Exceptions\RoleNotFoundException;
use App\Modules\Role\Domain\Contracts\RoleRepositoryContract;
use App\Modules\Role\Domain\Entities\RoleEntity;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;
use App\Shared\Infrastructure\Exceptions\DatabaseException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

class RoleRepository implements RoleRepositoryContract
{
    /**
     * Retrieve all roles.
     *
     * NOTE:
     * This method returns infrastructure models intentionally.
     * Mapping to domain entities should happen at the Application layer if needed.
     *
     * @return Collection<int, Role>
     */
    public function getAllRoles(): Collection
    {
        return Role::query()->get();
    }

    /**
     * Retrieve a role by its unique identifier.
     *
     * @param GenericId $roleId
     * @return Role|null
     */
    public function findByRoleId(GenericId $roleId): ?Role
    {
        return Role::find($roleId->value());
    }

    /**
     * Retrieve a role by slug.
     *
     * @param LowerText $slug
     * @return Role|null
     */
    public function findBySlug(LowerText $slug): ?Role
    {
        return Role::Slug($slug->value())->first();
    }

    /**
     * Create a new role in the system and record an audit log.
     *
     * This method handles the persistence of a new Role entity.
     * It also logs the creation event for auditing purposes.
     *
     * @param RoleEntity $roleEntity
     * @throws DatabaseException if the role cannot be created
     */
    public function createRole(RoleEntity $roleEntity): void
    {

        try {
            $role = Role::create($roleEntity->toArray());

            // Record audit log for user creation
            insurance_audit(
                $role,
                AuditAction::ROLE_CREATED,
                null,
                ['status' => 'created']
            );
        } catch (Throwable $e) {
            /* Wrap low-level exception to avoid leaking infrastructure details */
            throw new DatabaseException('Unable to create role', 0, $e);
        }
    }

    public function updateRole(GenericId $roleId, RoleEntity $roleEntity): void
    {
        $role = $this->getOrFail($roleId);

        /**
         * Extract only non-null values from the entity
         */
        $updates = array_non_null_values($roleEntity->toArray());

        if ($updates === []) {
            return;
        }

        /**
         * Capture original values before update
         */
        $oldValues = array_old_values($role, $updates);

        $role->update($updates);

        insurance_audit(
            $role,
            AuditAction::ROLE_UPDATED,
            $oldValues,
            $updates
        );
    }

    /**
     * Retrieve a role or throw a domain exception.
     *
     * @param GenericId $userId
     * @return Role
     * @throws RoleNotFoundException
     */
    private function getOrFail(GenericId $roleId): Role
    {
        $user = $this->findByRoleId($roleId);

        if (! $user) {
            throw new RoleNotFoundException();
        }

        return $user;
    }
}
