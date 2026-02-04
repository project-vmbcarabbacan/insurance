<?php

namespace App\Modules\Customer\Domain\Entities;

use App\Shared\Domain\Enums\CustomerStatus;
use App\Shared\Domain\Enums\CustomerType;

final class PaginatedCustomerEntity
{
    public function __construct(
        public readonly int $per_page,
        public readonly ?CustomerStatus $status,
        public readonly ?CustomerType $type,
        public readonly ?string $keyword,
        public readonly ?array $dates
    ) {}
}
