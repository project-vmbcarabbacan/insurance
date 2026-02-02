<?php

namespace App\Modules\User\Infrastructure\Repositories;

use App\Models\User;
use App\Modules\User\Application\Exceptions\UserNotFoundException;
use App\Modules\User\Domain\Contracts\UserRepositoryContract;
use App\Modules\User\Domain\Entities\CreateUserEntity;
use App\Modules\User\Domain\Entities\PaginatedUserEntity;
use App\Modules\User\Domain\Entities\UserEntity;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Password;
use App\Shared\Infrastructure\Exceptions\DatabaseException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

class UserRepository implements UserRepositoryContract
{
    /**
     * Retrieve a paginated list of users with optional filtering.
     *
     * This method builds a query on the User model and applies the following filters:
     * 1. Status: Filters users based on their current status (active, inactive, suspended).
     * 2. Keyword: Searches across the user's name and email if a keyword is provided.
     * 3. Role: Filters users by their assigned role if a role_id is provided.
     *
     * It returns a paginated result set based on the specified `per_page` value in the
     * PaginatedUserEntity.
     *
     * Example usage:
     * ```php
     * $paginatedUsers = $userRepository->paginatedUser(
     *     new PaginatedUserEntity(
     *         status: GenericStatus::ACTIVE,
     *         per_page: 10,
     *         keyword: 'vincent',
     *         role_id: 2
     *     )
     * );
     * ```
     *
     * @param PaginatedUserEntity $paginatedUserEntity Contains filters and pagination info
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator Paginated list of users
     */
    public function paginatedUser(PaginatedUserEntity $paginatedUserEntity): ?LengthAwarePaginator
    {
        // Start building the query
        $query = User::query();

        // Filter users by status using enum
        $query = match ($paginatedUserEntity->status) {
            GenericStatus::ACTIVE => $query->active(),
            GenericStatus::INACTIVE => $query->inactive(),
            GenericStatus::SUSPENDED => $query->suspended(),
            default => $query,
        };

        // Filter by keyword in name or email if provided
        $query->when(
            $paginatedUserEntity->keyword,
            fn($q, $keyword) => $q->search($keyword)
        );

        // Filter by role_slug if provided
        $query->when(
            $paginatedUserEntity->role_slug,
            fn($q, $slug) => $q->whereHas(
                'role',
                fn($r) =>
                $r->where('slug', $slug)
            )
        );

        $query->whereHas('role', fn($q) => $q->noSuperAdmin());

        return $query->paginate($paginatedUserEntity->per_page);
    }

    /**
     * Create a new user in the system and record an audit log.
     *
     * This method handles the persistence of a new User entity.
     * It also logs the creation event for auditing purposes.
     *
     * @param CreateUserEntity $createUserEntity
     * @throws DatabaseException if the user cannot be created
     */
    public function createUser(CreateUserEntity $createUserEntity): void
    {
        try {
            $user = User::create($createUserEntity->toArray());

            // Record audit log for user creation
            insurance_audit(
                $user,
                AuditAction::USER_CREATED,
                null,
                ['status' => 'created']
            );
        } catch (Throwable $e) {
            /* Wrap low-level exception to avoid leaking infrastructure details */
            throw new DatabaseException('Unable to create user', 0, $e);
        }
    }

    /**
     * Retrieve a user by its unique identifier.
     *
     * @param GenericId $userId
     * @return User|null
     */
    public function findUserById(GenericId $userId): ?User
    {
        return User::find($userId->value());
    }

    /**
     * Retrieve a user by email address.
     *
     * @param Email $email
     * @return User|null
     */
    public function findUserByEmail(Email $email): ?User
    {
        return User::email($email->value())->first();
    }

    /**
     * Update the user's password and record an audit log.
     *
     * @param GenericId $userId
     * @param Password  $password
     * @throws UserNotFoundException
     */
    public function updatePassword(GenericId $userId, Password $password): void
    {
        $user = $this->getOrFail($userId);

        $oldValues = [
            'password' => '********',
        ];

        $user->update([
            'password' => $password->value(),
        ]);

        insurance_audit(
            $user,
            AuditAction::PASSWORD_CHANGED,
            $oldValues,
            ['password' => '********']
        );
    }

    /**
     * Activate a user account.
     *
     * @param GenericId $userId
     */
    public function activateUser(GenericId $userId): void
    {
        $this->updateStatus($userId, GenericStatus::ACTIVE, AuditAction::USER_ACTIVATED);
    }

    /**
     * Deactivate a user account.
     *
     * @param GenericId $userId
     */
    public function deactivateUser(GenericId $userId): void
    {
        $this->updateStatus($userId, GenericStatus::INACTIVE, AuditAction::USER_DEACTIVATED);
    }

    /**
     * Suspend a user account.
     *
     * @param GenericId $userId
     */
    public function suspendUser(GenericId $userId): void
    {
        $this->updateStatus($userId, GenericStatus::SUSPENDED, AuditAction::USER_SUSPENDED);
    }

    /**
     * Soft-delete a user by updating its status.
     *
     * @param GenericId $userId
     */
    public function deleteUser(GenericId $userId): void
    {
        $this->updateStatus($userId, GenericStatus::DELETED, AuditAction::USER_DELETED);
    }

    /**
     * Update user profile fields and audit only changed values.
     *
     * @param GenericId  $userId
     * @param UserEntity $userEntity
     * @throws UserNotFoundException
     */
    public function updateProfile(GenericId $userId, UserEntity $userEntity): void
    {
        $user = $this->getOrFail($userId);

        /**
         * Extract only non-null values from the entity
         */
        $updates = array_non_null_values($userEntity->toArray());

        if ($updates === []) {
            return;
        }

        /**
         * Capture original values before update
         */
        $oldValues = array_old_values($user, $updates);


        $user->update($updates);

        insurance_audit(
            $user,
            AuditAction::USER_UPDATED,
            $oldValues,
            $updates
        );
    }

    /**
     * Update user status and record an audit entry.
     *
     * @param GenericId     $userId
     * @param GenericStatus $status
     * @param AuditAction   $action
     */
    private function updateStatus(
        GenericId $userId,
        GenericStatus $status,
        AuditAction $action
    ): void {
        $user = $this->getOrFail($userId);

        $oldValues = [
            'status' => $user->status,
        ];

        $user->update([
            'status' => $status->value,
        ]);

        insurance_audit(
            $user,
            $action,
            $oldValues,
            ['status' => $status->value]
        );
    }

    /**
     * Retrieve a user or throw a domain exception.
     *
     * @param GenericId $userId
     * @return User
     * @throws UserNotFoundException
     */
    private function getOrFail(GenericId $userId): User
    {
        $user = $this->findUserById($userId);

        if (! $user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
