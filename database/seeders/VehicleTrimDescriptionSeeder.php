<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VehicleTrim;
use App\Shared\Domain\Enums\GenericStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleTrimDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = storage_path('app/private/car-api/trims.json');

        if (!file_exists($path)) {
            throw new \RuntimeException('Trim file not found at: ' . $path);
        }

        $json = file_get_contents($path);
        $trims = json_decode($json, true);

        $result = [];
        if (!$trims) return;

        foreach ($trims as $trim) {
            if (
                empty($trim['bodies'])
            ) {
                continue;
            }


            $result[] = [
                'reference_id'  => $trim['id'],
                'vehicle_make_id'  => $trim['make_id'],
                'vehicle_model_id'  => $trim['model_id'],
                'year'  => $trim['year'],
                'name'  => $trim['trim'],
                'description'  => $trim['description'],
                'msrp'  => $trim['msrp'],
                'type'  => $trim['bodies'][0]['type'],
                'seats' => $trim['bodies'][0]['seats'],
                'doors' => $trim['bodies'][0]['doors'],
                'engine_type'   => $trim['engines'] && $trim['engines'][0] ? $trim['engines'][0]['engine_type'] : '',
                'fuel_type'     => $trim['engines'] && $trim['engines'][0] ? $trim['engines'][0]['fuel_type'] : '',
                'cylinders'     => $trim['engines'] && $trim['engines'][0] ? $trim['engines'][0]['cylinders'] : '',
            ];
        }

        $chunks = array_chunk($result, 100);

        foreach ($chunks as $chunk) {
            VehicleTrim::upsert(
                $chunk,
                ['reference_id', 'vehicle_make_id', 'vehicle_model_id', 'year', 'description'],
                ['type', 'seats', 'doors', 'engine_type', 'fuel_type', 'cylinders']
            );
        }
    }
}
