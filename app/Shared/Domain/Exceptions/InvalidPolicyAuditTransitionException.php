<?php

namespace App\Shared\Domain\Exceptions;

use DomainException;

class InvalidPolicyAuditTransitionException extends DomainException
{
    public function __construct(
        string $action,
        ?string $from,
        string $to
    ) {
        parent::__construct(
            "Invalid policy audit transition. Action: {$action}, From: {$from}, To: {$to}"
        );
    }
}
