<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Application\DTOs\UserStatusDto;
use App\Modules\User\Application\Services\UserService;
use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\Exceptions\InvalidValueException;

class UpdateUserStatus
{
    public function __construct(
        protected UserService $user_service
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
            $this->user_service->deactivateUser($userStatusDto->user_id),

            GenericStatus::SUSPENDED =>
            $this->user_service->suspendUser($userStatusDto->user_id),

            GenericStatus::DELETED =>
            $this->user_service->deleteUser($userStatusDto->user_id),

            // ACTIVE, DRAFT, or any future "enabled" states
            default =>
            $this->user_service->activeUser($userStatusDto->user_id),
        };
    }
}
