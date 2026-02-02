<?php

namespace Database\Seeders;

use App\Models\User;
use App\Shared\Domain\Enums\GenericStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(InsuranceProductSeeder::class);
        $this->call(DocumentTypesSeeder::class);
        $this->call(SystemUserSeeder::class);
        $this->call(VehicleMakeSeeder::class);
        $this->call(VehicleModelSeeder::class);
    }
}
