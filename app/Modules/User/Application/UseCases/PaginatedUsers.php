<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Application\DTOs\PaginatedUserDto;
use App\Modules\User\Application\Services\UserService;
use App\Modules\User\Domain\Entities\PaginatedUserEntity;

class PaginatedUsers
{
    /**
     * @param UserService $user_service Handles user-related domain operations
     */
    public function __construct(
        protected UserService $user_service,
    ) {}

    public function execute(PaginatedUserDto $paginatedUserDto)
    {

        $entity = new PaginatedUserEntity(
            status: $paginatedUserDto->status,
            per_page: $paginatedUserDto->per_page,
            keyword: $paginatedUserDto->keyword,
            role_slug: $paginatedUserDto->role_slug
        );

        return $this->user_service->getPaginatedUsers($entity);
    }
}
