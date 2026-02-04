<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InsuranceProduct;
use App\Models\AgentAssignmentQueue;
use App\Models\AgentProductAccess;
use App\Models\User;
use App\Shared\Domain\Enums\RoleSlug;

class AgentAssignmentQueueSeeder extends Seeder
{
    public function run(): void
    {
        $agents = User::whereHas('role', function ($q) {
            $q->whereIn('slug', [RoleSlug::AGENT->value, RoleSlug::TEAM_LEAD->value]);
        })->get();

        $products = InsuranceProduct::all();

        foreach ($products as $product) {

            $position = 1;

            foreach ($agents as $agent) {

                $accessed = AgentProductAccess::where([
                    'agent_id' => $agent->id,
                    'insurance_product_code' => $product->code,
                    'is_active' => 1
                ])->exists();

                AgentAssignmentQueue::updateOrCreate(
                    [
                        'insurance_product_code' => $product->code,
                        'agent_id' => $agent->id,
                    ],
                    [
                        'position' => $position++,
                        'is_active' => $agent->status->value == 'active' && $accessed,
                    ]
                );
            }
        }
    }
}
