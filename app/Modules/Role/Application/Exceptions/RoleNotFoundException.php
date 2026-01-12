<?php

namespace App\Modules\Role\Application\Exceptions;

use App\Shared\Application\Exceptions\ApplicationLayerException;

final class RoleNotFoundException extends ApplicationLayerException
{
    public function __construct()
    {
        return parent::__construct('Role not found!');
    }

    public function errorCode(): string
    {
        return 'ROLE_NOT_FOUND';
    }

    public function statusCode(): int
    {
        return 404;
    }
}
