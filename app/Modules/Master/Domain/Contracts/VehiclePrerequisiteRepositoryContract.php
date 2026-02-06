<?php

namespace App\Modules\Master\Domain\Contracts;

use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\VehicleTrim;
use Illuminate\Database\Eloquent\Collection;

interface VehiclePrerequisiteRepositoryContract
{
    public function getMakes(int $year): ?Collection;
    public function getModels(int $year, int $make_id): ?Collection;
    public function getTrims(int $year, int $make_id, int $model_id): ?Collection;
    public function findMake(int $year): ?VehicleMake;
    public function findModel(int $year, int $make_id): ?VehicleModel;
    public function findTrim(int $year, int $make_id, int $model_id): ?VehicleTrim;
}
