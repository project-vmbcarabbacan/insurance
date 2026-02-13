<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Application\DTOs\UserStatusDto;
use App\Modules\User\Application\Services\UserService;
use App\Shared\Domain\Enums\GenericStatus;

class UpdateUserStatus
{
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Update the user's status based on the provided DTO.
     *
     * The DTO already guarantees a valid GenericStatus enum,
     * so no additional enum validation is required here.
     *
     * @param UserStatusDto $userStatusDto
     */
    public function execute(UserStatusDto $userStatusDto)
    {
        match ($userStatusDto->status) {
            GenericStatus::INACTIVE =>
            $this->userService->deactivateUser($userStatusDto->user_id),

            GenericStatus::SUSPENDED =>
            $this->userService->suspendUser($userStatusDto->user_id),

            GenericStatus::DELETED =>
            $this->userService->deleteUser($userStatusDto->user_id),

            // ACTIVE, DRAFT, or any future "enabled" states
            default =>
            $this->userService->activeUser($userStatusDto->user_id),
        };
    }
}
