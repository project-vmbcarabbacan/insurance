<?php

namespace App\Modules\User\Domain\Entities;

use App\Shared\Domain\Enums\GenericStatus;

final class PaginatedUserEntity
{
    public function __construct(
        public readonly GenericStatus $status,
        public readonly int $per_page,
        public readonly ?string $keyword,
        public readonly ?string $role_slug,
    ) {}
}
