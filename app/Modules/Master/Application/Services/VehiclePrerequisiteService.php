<?php

namespace App\Modules\Master\Application\Services;

use App\Modules\Master\Domain\Contracts\VehiclePrerequisiteRepositoryContract;

class VehiclePrerequisiteService
{
    public function __construct(
        protected VehiclePrerequisiteRepositoryContract $vehicle_prerequisite_repository_contract
    ) {}

    public function makes(int $year)
    {
        return $this->vehicle_prerequisite_repository_contract->getMakes($year);
    }

    public function make(int $year)
    {
        return $this->vehicle_prerequisite_repository_contract->findMake($year);
    }

    public function models(int $year, int $make_id)
    {
        return $this->vehicle_prerequisite_repository_contract->getModels($year, $make_id);
    }

    public function model(int $year, int $make_id)
    {
        return $this->vehicle_prerequisite_repository_contract->findModel($year, $make_id);
    }

    public function trims(int $year, int $make_id, int $model_id)
    {
        return $this->vehicle_prerequisite_repository_contract->getTrims($year, $make_id, $model_id);
    }

    public function trim(int $year, int $make_id, int $model_id)
    {
        return $this->vehicle_prerequisite_repository_contract->findTrim($year, $make_id, $model_id);
    }
}
