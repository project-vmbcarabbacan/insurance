<?php

namespace Database\Seeders;

use App\Models\User;
use App\Shared\Domain\Enums\GenericStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'system@test.com',
            ],
            [
                'name' => 'System',
                'password' => bcrypt('M0@3r1k5'),
                'status' => GenericStatus::ACTIVE,
                'role_id' => 1
            ]
        );
    }
}
