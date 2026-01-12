<?php

namespace App\Modules\Authentication\Application\Services;

use App\Modules\Authentication\Application\DTOs\LoginDto;
use App\Modules\Authentication\Application\DTOs\RegisterDto;
use App\Modules\Authentication\Application\Exceptions\AuthenticationFailedException;
use App\Modules\Authentication\Domain\Contracts\AuthenticationRepositoryContract;
use App\Modules\Authentication\Domain\Entities\LoginEntity;
use App\Modules\Authentication\Domain\Entities\RegisterEntity;
use App\Modules\User\Application\Exceptions\UserNotFoundException;
use App\Modules\User\Domain\Contracts\UserRepositoryContract;
use App\Shared\Domain\Enums\AuditAction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthenticationService
{
    /**
     * Constructor.
     *
     * Injects the UserRepositoryContract dependency which is responsible for
     * handling user persistence, retrieval, and audit logging.
     *
     * By using the repository contract, this class depends on an abstraction
     * rather than a concrete implementation, keeping the code loosely coupled
     * and testable.
     *
     * @param UserRepositoryContract $user_repository_contract
     */
    public function __construct(
        protected UserRepositoryContract $user_repository_contract
    ) {}

    /**
     * Attempt to authenticate a user with the given credentials.
     *
     * This method validates the user's email and password, records audit logs
     * for both successful and failed login attempts, and returns the authenticated user.
     *
     * @param LoginDto $login
     * @return User
     * @throws UserNotFoundException if no user exists with the given email
     * @throws AuthenticationFailedException if the password is invalid
     */
    public function login(LoginDto $login)
    {
        $entity = new LoginEntity(
            email: $login->email,
            password: $login->password,
            ip_address: $login->ip_address
        );

        $user = $this->user_repository_contract->findUserByEmail($entity->email);

        if (!$user) {
            throw new UserNotFoundException();
        }

        if (!hash_equals($entity->password->value(), $user->password)) {
            insuranceAudit(
                $user,
                AuditAction::USER_LOGIN_FAILED,
                null,
                ['ip' => $entity->ip_address->value()]
            );

            throw new AuthenticationFailedException();
        }

        insuranceAudit(
            $user,
            AuditAction::USER_LOGGED_IN,
            null,
            ['ip' => $entity->ip_address->value()]
        );

        return $user;
    }

    /**
     * Attempt to authenticate a user using the provided credentials.
     *
     * Uses Laravel's built-in Auth facade.
     *
     * @param array $credentials
     * @return bool True if authentication succeeded, false otherwise
     */
    public function attemp(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    /**
     * Log out the currently authenticated user.
     *
     * Uses Laravel's Auth facade and records an audit log for the logout action.
     *
     * @return void
     */
    public function logout(): void
    {
        $user = Auth::user();

        if ($user) {
            Auth::logout();

            // Audit log for logout
            insuranceAudit(
                $user,
                AuditAction::USER_LOGGED_OUT,
                null,
                ['logged_out_at' => Carbon::now()]
            );
        }
    }
}
