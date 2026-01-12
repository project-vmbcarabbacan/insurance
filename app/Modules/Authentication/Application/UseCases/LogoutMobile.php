<?php

namespace App\Modules\Authentication\Application\UseCases;

use App\Modules\Authentication\Application\Services\SanctumTokenService;

/**
 * Use case responsible for logging out a user in a mobile context.
 *
 * This handles the revocation of Laravel Sanctum API tokens for the
 * authenticated user. It supports revoking either the current token
 * or all active tokens, depending on the input flag.
 *
 * This use case delegates token management to the SanctumTokenService,
 * keeping orchestration logic separate from infrastructure concerns.
 */
class LogoutMobile
{
    /**
     * Inject the SanctumTokenService which handles revoking API tokens.
     *
     * @param SanctumTokenService $sanctum_token_service
     */
    public function __construct(
        protected SanctumTokenService $sanctum_token_service
    ) {}

    /**
     * Execute the logout process for a mobile client.
     *
     * @param bool $revoke_all If true, revoke all tokens of the user;
     *                         otherwise, revoke only the current token.
     *
     * @return void
     */
    public function execute(bool $revoke_all = false): void
    {
        if ($revoke_all) {
            // Revoke all tokens for the user and record an audit log
            $this->sanctum_token_service->revokeAllTokens();
        } else {
            // Revoke only the current token and record an audit log
            $this->sanctum_token_service->revokeCurrentToken();
        }
    }
}
