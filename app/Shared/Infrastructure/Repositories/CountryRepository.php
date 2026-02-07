<?php

namespace App\Shared\Infrastructure\Repositories;

use App\Shared\Domain\Contracts\CountryRepositoryContract;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

final class CountryRepository implements CountryRepositoryContract
{
    public function phoneCountryCodes(): array
    {
        return Cache::rememberForever('phone_country_codes', function () {
            $path = storage_path('app/private/countries.json');

            if (!file_exists($path)) {
                throw new \RuntimeException('Countries file not found at: ' . $path);
            }

            $json = file_get_contents($path);
            $countries = json_decode($json, true);

            $result = [];
            if (!$countries) return $result;

            foreach ($countries as $country) {
                if (
                    empty($country['idd']['root']) ||
                    empty($country['idd']['suffixes'])
                ) {
                    continue;
                }

                foreach ($country['idd']['suffixes'] as $suffix) {
                    $result[] = [
                        'label' => $country['name']['common'],
                        'value' => $country['idd']['root'] . $suffix,
                    ];
                }
            }

            usort(
                $result,
                fn($a, $b) =>
                strcmp($a['label'], $b['label'])
            );

            return $result;
        });
    }

    public function countries(): array
    {
        return Cache::rememberForever('countries.select', function () {
            $path = storage_path('app/private/countries.json');

            // Check if file exists
            if (!file_exists($path)) {
                return [];
            }

            // Read file contents
            $json = file_get_contents($path);
            if ($json === false) {
                return [];
            }

            // Decode JSON
            $countriesArray = json_decode($json, true);
            if (!is_array($countriesArray)) {
                return [];
            }

            // Map and sort
            $countries = collect($countriesArray)
                ->map(function ($c) {
                    // Guard against missing keys
                    return [
                        'value' => $c['cca2'] ?? null,
                        'label' => $c['name']['common'] ?? null,
                    ];
                })
                ->filter(fn($c) => $c['value'] && $c['label']) // remove invalid entries
                ->sortBy(fn($c) => strtolower($c['label'])) // case-insensitive sort
                ->values()
                ->toArray();

            return $countries;
        });
    }

    public function findCountryByValue(string $value): ?array
    {
        $value = strtoupper(trim($value));

        return $this->countriesIndexed()[$value] ?? null;
    }

    private function countriesIndexed(): array
    {
        return Cache::rememberForever('countries.select.indexed', function () {
            return collect($this->countries())
                ->keyBy('value')
                ->toArray();
        });
    }
}
