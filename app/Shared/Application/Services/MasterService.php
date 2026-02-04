<?php

namespace App\Shared\Application\Services;

use App\Shared\Domain\Contracts\CountryRepositoryContract;
use Illuminate\Support\Facades\Storage;

class MasterService
{
    public function __construct(
        protected CountryRepositoryContract $country_repository_contract
    ) {}

    public function getPhoneCountryCode(): array
    {
        return $this->country_repository_contract->phoneCountryCodes();
    }
    public function countries(): array
    {
        return $this->country_repository_contract->countries();
    }
}
