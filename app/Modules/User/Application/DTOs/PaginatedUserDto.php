<?php

namespace App\Modules\User\Application\DTOs;

use App\Shared\Domain\Enums\GenericStatus;

class PaginatedUserDto
{
    public function __construct(
        public readonly GenericStatus $status,
        public readonly int $per_page,
        public readonly ?string $keyword,
        public readonly ?string $role_slug,
    ) {}
}
