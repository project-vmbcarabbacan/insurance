<?php

namespace App\Shared\Domain\Contracts;

interface CountryRepositoryContract
{
    public function phoneCountryCodes(): array;
    public function countries(): array;
    public function findCountryByValue(string $value): ?array;
}
