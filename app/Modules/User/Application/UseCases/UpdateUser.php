<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\Role\Application\Exceptions\RoleNotFoundException;
use App\Modules\Role\Application\Services\RoleService;
use App\Modules\User\Application\DTOs\UpdateUserDto;
use App\Modules\User\Application\Exceptions\UserNotFoundException;
use App\Modules\User\Application\Services\UserService;
use App\Modules\User\Domain\Entities\UserEntity;
use App\Shared\Domain\ValueObjects\GenericId;

class UpdateUser
{
    public function __construct(
        protected UserService $userService,
        protected RoleService $roleService
    ) {}

    /**
     * Update a user's profile information.
     *
     * This use case:
     * - Ensures the user exists
     * - Maps DTO data into a domain entity
     * - Delegates the update operation to the UserService
     *
     * @param UpdateUserDto $updateUserDto
     *
     * @throws UserNotFoundException When the user does not exist
     */
    public function execute(UpdateUserDto $updateUserDto)
    {
        // Retrieve the existing user or fail fast
        $existingUser = $this->userService->getById($updateUserDto->user_id);

        if (! $existingUser) {
            throw new UserNotFoundException();
        }

        // Ensure role exists
        $role = $this->roleService->getRoleBySlug($updateUserDto->role);
        if (! $role)
            throw new RoleNotFoundException();

        // Create a domain entity containing updated profile data
        $userEntity = new UserEntity(
            name: $updateUserDto->name,
            email: $updateUserDto->email,
            role_id: GenericId::fromId($role->id),
        );

        // Persist profile changes
        $this->userService->updateProfile($updateUserDto->user_id, $userEntity);
    }
}
