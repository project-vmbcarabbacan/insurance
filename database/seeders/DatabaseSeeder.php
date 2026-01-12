<?php

namespace Database\Seeders;

use App\Models\User;
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

        User::factory()->create([
            'name' => 'System',
            'email' => 'system@example.com',
            'role_id' => 1,
            'status' => 1,
        ]);
    }
}
