<?php

namespace App\Modules\Customer\Application\DTOs;

use App\Shared\Domain\Enums\CustomerStatus;
use App\Shared\Domain\Enums\CustomerType;

class PaginatedCustomerDto
{
    public function __construct(
        public readonly int $per_page,
        public readonly ?CustomerStatus $status,
        public readonly ?CustomerType $type,
        public readonly ?string $keyword,
        public readonly ?array $dates
    ) {}
}
