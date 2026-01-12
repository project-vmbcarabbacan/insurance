<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Application\DTOs\UpdateUserDto;
use App\Modules\User\Application\Exceptions\UserNotFoundException;
use App\Modules\User\Application\Services\UserService;
use App\Modules\User\Domain\Entities\UserEntity;

class UpdateUser
{
    public function __construct(
        protected UserService $user_service
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
        $existingUser = $this->user_service->getById($updateUserDto->user_id);

        if (! $existingUser) {
            throw new UserNotFoundException();
        }

        // Create a domain entity containing updated profile data
        $userEntity = new UserEntity(
            name: $updateUserDto->name,
            email: $updateUserDto->email
        );

        // Persist profile changes
        $this->user_service->updateProfile($updateUserDto->user_id, $userEntity);
    }
}
