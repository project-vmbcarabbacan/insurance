<?php

namespace App\Modules\Authentication\Application\UseCases;

use App\Modules\Authentication\Application\DTOs\LoginDto;
use App\Modules\Authentication\Application\Services\AuthenticationService;

/**
 * Use case responsible for logging in a user via a mobile application.
 *
 * This class orchestrates the mobile login flow by delegating the
 * authentication process to the AuthenticationService.
 *
 * In mobile contexts, authentication typically returns a token (e.g., Sanctum or JWT)
 * rather than using a session like in SPA flows.
 */
class LoginMobile
{
    /**
     * Inject the AuthenticationService which handles the core authentication logic.
     *
     * @param AuthenticationService $authentication_service
     */
    public function __construct(
        protected AuthenticationService $authentication_service
    ) {}

    /**
     * Execute the login process for a mobile client.
     *
     * This method delegates the login flow to the AuthenticationService,
     * which handles:
     * - Validating user credentials
     * - Recording login audits (if applicable)
     * - Returning authentication token or user entity for mobile use
     *
     * @param LoginDto $login The login credentials DTO
     * @return mixed The result of the authentication (token or user object)
     */
    public function execute(LoginDto $login)
    {
        return $this->authentication_service->login($login);
    }
}
