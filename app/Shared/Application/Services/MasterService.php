<?php

namespace App\Shared\Application\Services;

use App\Shared\Domain\Contracts\CountryRepositoryContract;
use Illuminate\Support\Facades\Storage;

class MasterService
{
    public function __construct(
        protected CountryRepositoryContract $countryRepositoryContract
    ) {}

    public function getPhoneCountryCode(): array
    {
        return $this->countryRepositoryContract->phoneCountryCodes();
    }

    public function countries(): array
    {
        return $this->countryRepositoryContract->countries();
    }

    public function findCountryByValue(string $value): array
    {
        return $this->countryRepositoryContract->findCountryByValue($value);
    }
}
