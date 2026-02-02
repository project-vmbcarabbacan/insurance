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
        protected UserService $user_service,
        protected RoleService $role_service,
        protected InsuranceProductService $insurance_product_service,
        protected AgentProductService $agent_product_service
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
        if ($this->user_service->getEmail($createUserDto->email)) {
            throw new EmailAlreadyExistsException();
        }

        // Ensure role exists
        $role = $this->role_service->getRoleBySlug($createUserDto->role);
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
        $user = $this->user_service->createUser($createUserEntity);

        if (in_array($createUserDto->role, [RoleSlug::AGENT->value, RoleSlug::TEAM_LEAD->value])) {
            $products = $this->insurance_product_service->getAllProduct();
            $accessed = [];
            foreach ($products as $product) {
                $accessed[] = [
                    'agent_id' => $user->id,
                    'insurance_product_code' => $product->code,
                    'is_active' => false
                ];
            }

            $this->agent_product_service->addAccessByAgentId(GenericId::fromId($user->id), $accessed);
        }
    }
}
