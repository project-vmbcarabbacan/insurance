<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\InsuranceProduct;

class InsuranceProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product_insurances = [
            'vehicle',
            'health',
            'travel',
            'pet',
            'home'
        ];

        foreach ($product_insurances as $name) {
            // $code = random_string(InsuranceProduct::class, 20, 'code');

            if (!DB::table('insurance_products')->where('name', $name)->exists()) {
                DB::table('insurance_products')->insert([
                    'code' => $name,
                    'name' => ucwords($name),
                ]);
            }
        }
    }
}
