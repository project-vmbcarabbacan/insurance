<?php

namespace App\Modules\User\Application\Services;

use App\Models\User;
use App\Modules\User\Domain\Contracts\UserRepositoryContract;
use App\Modules\User\Domain\Entities\CreateUserEntity;
use App\Modules\User\Domain\Entities\PaginatedUserEntity;
use App\Modules\User\Domain\Entities\UserEntity;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Password;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        protected UserRepositoryContract $user_repository_contract
    ) {}

    public function getPaginatedUsers(PaginatedUserEntity $paginatedUserEntity): LengthAwarePaginator
    {
        return $this->user_repository_contract->paginatedUser($paginatedUserEntity);
    }

    /**
     * Retrieve a user by email address.
     *
     * This method delegates the lookup to the repository layer.
     * It is commonly used to:
     * - Check if a user already exists
     * - Fetch user data for authentication or validation
     *
     * @param Email $email
     * @return User|null Returns the User model if found, otherwise null
     */
    public function getEmail(Email $email): ?User
    {
        return $this->user_repository_contract->findUserByEmail($email);
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * Used by application use cases to verify existence
     * or retrieve the current user state.
     *
     * @param GenericId $userId
     * @return User|null Returns the User model if found, otherwise null
     */
    public function getById(GenericId $userId): ?User
    {
        return $this->user_repository_contract->findUserById($userId);
    }

    /**
     * Create a new user.
     *
     * Accepts a domain entity containing all required user data
     * and delegates persistence to the repository layer.
     *
     * @param CreateUserEntity $createUserEntity
     */
    public function createUser(CreateUserEntity $createUserEntity): ?User
    {
        return $this->user_repository_contract->createUser($createUserEntity);
    }

    /**
     * Update a user's password.
     *
     * The password value object is expected to be already validated
     * and securely hashed before reaching this method.
     *
     * @param GenericId $userId
     * @param Password $password
     */
    public function updatePassword(GenericId $userId, Password $password): void
    {
        $this->user_repository_contract->updatePassword($userId, $password);
    }

    /**
     * Activate a user account.
     *
     * Typically used when:
     * - Re-enabling an inactive user
     * - Completing account verification
     *
     * @param GenericId $userId
     */
    public function activeUser(GenericId $userId): void
    {
        $this->user_repository_contract->activateUser($userId);
    }

    /**
     * Deactivate a user account.
     *
     * Used to temporarily disable user access
     * without deleting their data.
     *
     * @param GenericId $userId
     */
    public function deactivateUser(GenericId $userId): void
    {
        $this->user_repository_contract->deactivateUser($userId);
    }

    /**
     * Suspend a user account.
     *
     * Typically used for policy violations or administrative actions.
     * Suspension may differ from deactivation depending on business rules.
     *
     * @param GenericId $userId
     */
    public function suspendUser(GenericId $userId): void
    {
        $this->user_repository_contract->suspendUser($userId);
    }

    /**
     * Permanently delete a user account.
     *
     * This operation is destructive and should be used with caution.
     * Depending on implementation, this may be a soft or hard delete.
     *
     * @param GenericId $userId
     */
    public function deleteUser(GenericId $userId): void
    {
        $this->user_repository_contract->deleteUser($userId);
    }

    /**
     * Update a user's profile information.
     *
     * Accepts a domain entity containing updated profile fields
     * such as name or email, and delegates persistence to the repository.
     *
     * @param GenericId $userId
     * @param UserEntity $userEntity
     */
    public function updateProfile(GenericId $userId, UserEntity $userEntity): void
    {
        $this->user_repository_contract->updateProfile($userId, $userEntity);
    }
}
