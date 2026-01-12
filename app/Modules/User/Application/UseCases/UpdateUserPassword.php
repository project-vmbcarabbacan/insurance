<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Application\DTOs\UpdatePasswordDto;
use App\Modules\User\Application\Exceptions\PasswordsNotMatchException;
use App\Modules\User\Application\Exceptions\UserNotFoundException;
use App\Modules\User\Application\Services\UserService;

/**
 * Use case responsible for updating a user's password.
 *
 * Responsibilities:
 * - Validate password confirmation
 * - Ensure the user exists
 * - Delegate password update to the UserService
 *
 * This class contains application-level orchestration only.
 */
class UpdateUserPassword
{
    /**
     * @param UserService $user_service Handles user-related domain operations
     */
    public function __construct(
        protected UserService $user_service
    ) {}

    /**
     * Execute the update password use case.
     *
     * @param UpdatePasswordDto $updatePasswordDto Password update request data
     *
     * @throws PasswordsNotMatchException If password and confirmation do not match
     * @throws UserNotFoundException If the user does not exist
     *
     * @return void
     */
    public function execute(UpdatePasswordDto $updatePasswordDto)
    {
        // Ensure password and confirmation match
        if (! $updatePasswordDto->password->equals($updatePasswordDto->confirm_password)) {
            throw new PasswordsNotMatchException();
        }

        // Ensure user exists
        if (! $this->user_service->getById($updatePasswordDto->user_id)) {
            throw new UserNotFoundException();
        }

        // Delegate password update to the domain service
        $this->user_service->updatePassword($updatePasswordDto->user_id, $updatePasswordDto->password);
    }
}
