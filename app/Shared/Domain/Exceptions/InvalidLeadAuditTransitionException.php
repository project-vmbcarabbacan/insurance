<?php

namespace App\Shared\Domain\Exceptions;

use DomainException;

final class InvalidLeadAuditTransitionException extends DomainException
{
    public function __construct(
        string $action,
        ?string $from,
        ?string $to
    ) {
        parent::__construct(
            sprintf(
                'Invalid lead audit transition. Action: %s, From: %s, To: %s',
                $action,
                $from ?? 'null',
                $to ?? 'null'
            )
        );
    }
}
