<?php

namespace App\Modules\Authentication\Application\UseCases;

use App\Modules\Authentication\Application\DTOs\LoginDto;
use App\Modules\Authentication\Application\Exceptions\AuthenticationNotAuthorizedException;
use App\Modules\Authentication\Application\Exceptions\AuthenticationStatusNotActiveException;
use App\Modules\Authentication\Application\Services\AuthenticationService;
use App\Modules\Authentication\Infrastructure\Http\Requests\LoginRequest;
use App\Modules\User\Application\Services\UserService;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\Enums\GenericStatus;
use DomainException;

/**
 * Use case responsible for logging in a user via a Single Page Application (SPA).
 *
 * This orchestrates the authentication process, including:
 * - Validating credentials using the AuthenticationService
 * - Recording audit logs for both failed and successful login attempts
 * - Regenerating the session to prevent session fixation attacks
 *
 * This class separates orchestration logic from domain rules and
 * delegates user and audit operations to the UserService.
 */
class LoginSpa
{
    /**
     * Inject dependencies:
     * - AuthenticationService handles credential verification
     * - UserService handles user retrieval and audit logging
     *
     * @param AuthenticationService $authentication_service
     * @param UserService $user_service
     */
    public function __construct(
        protected AuthenticationService $authenticationService,
        protected UserService $userService
    ) {}

    /**
     * Execute the SPA login process with the provided credentials.
     *
     * Steps:
     * 1. Attempt to authenticate the user with the provided LoginDto.
     * 2. If authentication fails and the user exists, record a failed login audit.
     * 3. If authentication succeeds, retrieve the user and record a successful login audit.
     * 4. Regenerate the session to prevent session fixation attacks.
     *
     * @param LoginDto $login The login credentials DTO containing email and password
     * @param LoginRequest $request The HTTP request object to access IP and user agent
     * @return void
     *
     * @throws DomainException If authentication fails (invalid credentials)
     */
    public function execute(LoginDto $login, LoginRequest $request)
    {
        // Map DTO to credential array for the authentication service
        $credentials = [
            'email'    => $login->email->value(),
            'password' => $login->password->plain(),
        ];
        // Attempt authentication
        $authenticated = $this->authenticationService->attempt($credentials);

        // Retrieve user by email to record failed login audit if user exists
        $user = $this->userService->getEmail($login->email);

        // Handle customer trying to login in CRM
        if ($user->isCustomer()) {
            throw new AuthenticationNotAuthorizedException();
        }

        // Handle failed authentication
        if (! $authenticated) {

            if ($user) {
                $this->logAudit($user, AuditAction::USER_LOGIN_FAILED, $request);
            }

            // Throw domain-specific exception for invalid credentials
            throw new DomainException('Invalid Credentials');
        }


        // Authentication successful: retrieve user if not already loaded
        $user = auth('web')->user() ?? $this->userService->getEmail($login->email);

        if ($user->status !== GenericStatus::ACTIVE) {
            throw new AuthenticationStatusNotActiveException();
        }

        // Record successful login audit
        if ($user) {
            $this->logAudit($user, AuditAction::USER_LOGGED_IN, $request);
        }

        return $user;
    }

    /**
     * Log a login-related audit for the user.
     *
     * @param mixed $user The authenticated or attempted user
     * @param AuditAction $action The audit action to record
     * @param LoginRequest $request The HTTP request object
     * @return void
     */
    protected function logAudit(mixed $user, AuditAction $action, LoginRequest $request): void
    {
        insurance_audit(
            $user,
            $action,
            null,
            [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        );
    }
}
