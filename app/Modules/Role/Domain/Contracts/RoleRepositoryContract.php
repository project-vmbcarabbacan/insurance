<?php

namespace App\Modules\Role\Domain\Contracts;

use App\Models\Role;
use App\Modules\Role\Domain\Entities\RoleEntity;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;
use Illuminate\Database\Eloquent\Collection;

interface RoleRepositoryContract
{
    public function getAllRoles(): Collection;
    public function findByRoleId(GenericId $roleId): ?Role;
    public function findBySlug(LowerText $slug): ?Role;
    public function createRole(RoleEntity $roleEntity): void;
    public function updateRole(GenericId $roleId, RoleEntity $roleEntity): void;
}
