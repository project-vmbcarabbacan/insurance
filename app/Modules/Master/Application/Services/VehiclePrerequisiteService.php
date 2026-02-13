<?php

namespace App\Modules\Master\Application\Services;

use App\Modules\Master\Domain\Contracts\VehiclePrerequisiteRepositoryContract;

class VehiclePrerequisiteService
{
    public function __construct(
        protected VehiclePrerequisiteRepositoryContract $vehiclePrerequisiteRepositoryContract
    ) {}

    public function makes(int $year)
    {
        return $this->vehiclePrerequisiteRepositoryContract->getMakes($year);
    }

    public function make(int $year)
    {
        return $this->vehiclePrerequisiteRepositoryContract->findMake($year);
    }

    public function models(int $year, int $make_id)
    {
        return $this->vehiclePrerequisiteRepositoryContract->getModels($year, $make_id);
    }

    public function model(int $year, int $make_id)
    {
        return $this->vehiclePrerequisiteRepositoryContract->findModel($year, $make_id);
    }

    public function trims(int $year, int $make_id, int $model_id)
    {
        return $this->vehiclePrerequisiteRepositoryContract->getTrims($year, $make_id, $model_id);
    }

    public function trim(int $year, int $make_id, int $model_id)
    {
        return $this->vehiclePrerequisiteRepositoryContract->findTrim($year, $make_id, $model_id);
    }
}
