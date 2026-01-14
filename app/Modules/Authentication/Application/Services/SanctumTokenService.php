<?php

namespace App\Modules\Authentication\Application\Services;

use App\Modules\User\Domain\Contracts\UserRepositoryContract;
use App\Shared\Domain\Enums\AuditAction;

/**
 * Service responsible for managing Laravel Sanctum API tokens
 * for the authenticated user. Handles revoking the current token
 * or all tokens and records audit logs for these actions.
 */
class SanctumTokenService
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
     * Revoke the currently active API token of the authenticated user.
     *
     * If a token exists, it will be deleted and an audit entry will be created
     * specifying the revoked token ID and type as "current".
     *
     * @return void
     */
    public function revokeCurrentToken(): void
    {
        $user = auth()->user();

        // Early return if no authenticated user
        if (! $user) {
            return;
        }

        $token = $user->currentAccessToken();

        if (! $token) {
            return;
        }

        // Delete the current token
        $token->delete();

        // Record audit for revoking current token
        $this->logAudit($user, AuditAction::TOKEN_REVOKED, $token->id, 'current');
    }

    /**
     * Revoke all API tokens of the authenticated user.
     *
     * If the user has tokens, all will be deleted and an audit entry
     * will be recorded specifying all revoked token IDs and type as "all".
     *
     * @return void
     */
    public function revokeAllTokens(): void
    {
        $user = auth()->user();

        // Early return if no authenticated user
        if (! $user) {
            return;
        }

        // Collect all token IDs before deletion for auditing
        $tokenIds = $user->tokens()->pluck('id')->toArray();

        // Delete all tokens
        $user->tokens()->delete();

        // Record audit for revoking all tokens
        $this->logAudit($user, AuditAction::ALL_TOKENS_REVOKED, $tokenIds, 'all');
    }

    /**
     * Log a login-related audit for the user.
     *
     * @param mixed $user The authenticated or attempted user
     * @param AuditAction $action The audit action to record
     * @param mixed $token the token id or array of token ids
     * @param string $type type of token (eg. current | all)
     * @return void
     */
    protected function logAudit(mixed $user, AuditAction $action, mixed $token, string $type): void
    {
        $token = is_array($token) ? 'token_ids' : 'token_id';
        insurance_audit(
            $user,
            $action,
            null,
            [
                $token => $token,
                'type' => $type,
            ]
        );
    }
}
