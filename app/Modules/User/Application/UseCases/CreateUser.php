<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\Agent\Application\Services\AgentProductService;
use App\Modules\Master\Application\Services\InsuranceProductService;
use App\Modules\Role\Application\Exceptions\RoleNotFoundException;
use App\Modules\Role\Application\Services\RoleService;
use App\Modules\User\Application\DTOs\CreateUserDto;
use App\Modules\User\Application\Exceptions\EmailAlreadyExistsException;
use App\Modules\User\Application\Services\UserService;
use App\Modules\User\Domain\Entities\CreateUserEntity;
use App\Shared\Domain\Enums\RoleSlug;
use App\Shared\Domain\ValueObjects\GenericId;

/**
 * Use case responsible for creating a new user.
 *
 * This class coordinates:
 * - Email uniqueness validation
 * - Role existence validation
 * - Mapping DTO → Domain Entity
 * - Delegating persistence to UserService
 *
 * No infrastructure or framework logic should live here.
 */
class CreateUser
{
    /**
     * @param UserService $user_service Handles user-related domain operations
     * @param RoleService $role_service Handles role lookup and validation
     */
    public function __construct(
        protected UserService $userService,
        protected RoleService $roleService,
        protected InsuranceProductService $insuranceProductService,
        protected AgentProductService $agentProductService
    ) {}

    /**
     * Execute the create user use case.
     *
     * @param CreateUserDto $createUserDto Incoming user data from the application layer
     *
     * @throws EmailAlreadyExistsException If the email is already registered
     * @throws RoleNotFoundException If the provided role slug does not exist
     *
     * @return void
     */
    public function execute(CreateUserDto $createUserDto)
    {
        // Ensure email uniqueness
        if ($this->userService->getEmail($createUserDto->email)) {
            throw new EmailAlreadyExistsException();
        }

        // Ensure role exists
        $role = $this->roleService->getRoleBySlug($createUserDto->role);
        if (! $role)
            throw new RoleNotFoundException();

        // Map DTO to domain entity
        $createUserEntity = new CreateUserEntity(
            name: $createUserDto->name,
            email: $createUserDto->email,
            password: $createUserDto->password,
            role_id: GenericId::fromId($role->id),
            status: $createUserDto->status,
        );

        // Persist user through domain service
        $user = $this->userService->createUser($createUserEntity);

        if (in_array($createUserDto->role, [RoleSlug::AGENT->value, RoleSlug::TEAM_LEAD->value])) {
            $products = $this->insuranceProductService->getAllProduct();
            $accessed = [];
            foreach ($products as $product) {
                $accessed[] = [
                    'agent_id' => $user->id,
                    'insurance_product_code' => $product->code,
                    'is_active' => false
                ];
            }

            $this->agentProductService->addAccessByAgentId(GenericId::fromId($user->id), $accessed);
        }
    }
}
