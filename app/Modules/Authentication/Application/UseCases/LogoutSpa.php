<?php

namespace App\Modules\Authentication\Application\UseCases;

use App\Modules\Authentication\Application\Services\AuthenticationService;
use Illuminate\Support\Facades\Request;

/**
 * Use case responsible for logging out a user in a SPA (Single Page Application) context.
 *
 * This handles both:
 *  - Revoking the user's authentication session
 *  - Invalidating and regenerating the session token to prevent CSRF attacks
 *
 * The actual logout logic is delegated to the AuthenticationService, keeping this
 * use case focused on orchestration and domain flow.
 */
class LogoutSpa
{
    /**
     * Inject the AuthenticationService which handles core logout logic.
     *
     * @param AuthenticationService $authentication_service
     */
    public function __construct(
        protected AuthenticationService $authenticationService
    ) {}

    /**
     * Execute the SPA logout process.
     *
     * Steps:
     * 1. Call the AuthenticationService to revoke any authentication credentials (tokens/sessions).
     * 2. Invalidate the current HTTP session to remove session data.
     * 3. Regenerate the CSRF token to prevent reuse of the previous session.
     *
     * @param Request $request
     * @return void
     */
    public function execute(Request $request)
    {
        // Revoke authentication credentials (e.g., tokens, cookies)
        $this->authenticationService->logout();

        // Invalidate the current session to clear all session data
        $request->session()->invalidate();

        // Regenerate CSRF token to prevent session fixation attacks
        $request->session()->regenerateToken();
    }
}
