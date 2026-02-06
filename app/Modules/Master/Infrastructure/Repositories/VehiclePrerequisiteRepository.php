<?php

namespace App\Modules\Master\Infrastructure\Repositories;

use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\VehicleTrim;
use App\Modules\Master\Domain\Contracts\VehiclePrerequisiteRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class VehiclePrerequisiteRepository implements VehiclePrerequisiteRepositoryContract
{
    public function getMakes(int $year): ?Collection
    {
        return VehicleMake::where('year', $year)->get();
    }

    public function getModels(int $year, int $make_id): ?Collection
    {
        return VehicleModel::where([
            'year' => $year,
            'vehicle_make_id' => $make_id
        ])
            ->get();
    }

    public function getTrims(int $year, int $make_id, int $model_id): ?Collection
    {
        return VehicleTrim::where([
            'year' => $year,
            'vehicle_make_id' => $make_id,
            'vehicle_model_id' => $model_id
        ])
            ->select('name', DB::raw('MIN(reference_id) as reference_id'))
            ->groupBy('name')
            ->get();
    }

    public function findMake(int $year): ?VehicleMake
    {
        return VehicleMake::where('year', $year)->first();
    }

    public function findModel(int $year, int $make_id): ?VehicleModel
    {
        return VehicleModel::where([
            'year' => $year,
            'vehicle_make_id' => $make_id
        ])->first();
    }

    public function findTrim(int $year, int $make_id, int $model_id): ?VehicleTrim
    {
        return VehicleTrim::where([
            'year' => $year,
            'vehicle_make_id' => $make_id,
            'vehicle_model_id' => $model_id
        ])->first();
    }
}
