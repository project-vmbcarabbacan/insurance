<?php

namespace Database\Seeders;

use App\Models\AgentAssignmentSetting;
use Illuminate\Database\Seeder;
use App\Models\InsuranceProduct;

class AgentAssignmentSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maps = [
            'vehicle' => ['round_robin', 'least_loaded'],
            'health' => ['round_robin', 'least_loaded'],
            'travel' => ['round_robin', 'least_loaded'],
            'pet' => ['round_robin', 'least_loaded'],
            'home' => ['round_robin', 'least_loaded']
        ];

        foreach ($maps as $productCode => $strategies) {

            $product = InsuranceProduct::where('code', $productCode)->first();

            if (!$product) {
                $this->command?->warn("Product {$productCode} not found. Skipping.");
                continue;
            }

            foreach ($strategies as $strategy) {
                AgentAssignmentSetting::updateOrCreate(
                    [
                        'insurance_product_code' => $product->code,
                        'strategy' => $strategy
                    ],
                    [
                        'max_active_leads_per_agent' =>
                        $strategy === 'least_loaded' ? 10 : 0,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
