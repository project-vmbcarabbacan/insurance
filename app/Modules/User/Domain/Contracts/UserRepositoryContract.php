<?php

namespace App\Modules\User\Domain\Contracts;

use App\Models\User;
use App\Modules\User\Domain\Entities\CreateUserEntity;
use App\Modules\User\Domain\Entities\UserEntity;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\Password;
use App\Shared\Domain\ValueObjects\GenericId;

interface UserRepositoryContract
{
    public function createUser(CreateUserEntity $createUserEntity): void;
    public function findUserById(GenericId $userId): ?User;
    public function findUserByEmail(Email $email): ?User;
    public function updatePassword(GenericId $userId, Password $password): void;
    public function activateUser(GenericId $userId): void;
    public function deactivateUser(GenericId $userId): void;
    public function suspendUser(GenericId $userId): void;
    public function deleteUser(GenericId $userId): void;
    public function updateProfile(GenericId $userId, UserEntity $userEntity): void;
}
