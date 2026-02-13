<?php

namespace App\Shared\Infrastructure\Http\Controllers;

use App\Modules\Master\Application\Services\VehiclePrerequisiteService;
use App\Modules\Master\Infrastructure\Http\Requests\VehicleMakeRequest;
use App\Modules\Master\Infrastructure\Http\Requests\VehicleModelRequest;
use App\Modules\Master\Infrastructure\Http\Requests\VehicleTrimRequest;
use App\Shared\Application\Services\MasterService;
use App\Shared\Domain\Enums\Emirates;
use App\Shared\Domain\Enums\GenderType;
use App\Shared\Infrastructure\Http\Resources\VehiclePrerequisiteResource;
use App\Shared\Domain\Enums\YesNo;
use App\Shared\Domain\Enums\HealthExistingInsurance;
use App\Shared\Domain\Enums\HealthInsuranceFor;
use App\Shared\Domain\Enums\HealthInsureTo;
use App\Shared\Domain\Enums\MaritalStatus;
use App\Shared\Domain\Enums\MedicalCondition;
use App\Shared\Domain\Enums\Relationship;
use App\Shared\Domain\Enums\Salary;
use Illuminate\Support\Facades\Request;

class HealthController
{
    public function __construct(
        protected VehiclePrerequisiteService $vehiclePrerequisiteService,
        protected MasterService $masterService
    ) {}

    public function leadHealth(Request $request)
    {

        $countries = $this->masterService->countries();

        return response()->json([
            'message' => 'Manage lead health prerequisites',
            'data' => [
                'insurance_fors' => HealthInsuranceFor::toDropdownArray(),
                'insure_tos' => HealthInsureTo::toDropdownArray(),
                'existing_insurances' => HealthExistingInsurance::toDropdownArray(),
                'salaries' => Salary::toDropdownArray(),
                'genders' => GenderType::toDropdownArray(),
                'yes_no' => YesNo::toDropdownArray(),
                'emirates' => Emirates::toDropdownArray(),
                'relationships' => Relationship::toDropdownArray(),
                'medical_conditions' => MedicalCondition::toDropdownArray(),
                'marital_statuses' => MaritalStatus::toDropdownArray(),
                'countries' => $countries,
            ]
        ]);
    }

    public function getMakes(VehicleMakeRequest $request)
    {
        $makes = $this->vehiclePrerequisiteService->makes($request->year);

        return response()->json([
            'message' => 'Vehicle makes',
            'data' => VehiclePrerequisiteResource::collection($makes)
        ]);
    }

    public function getModels(VehicleModelRequest $request)
    {
        $models = $this->vehiclePrerequisiteService->models($request->year, $request->make_id);

        return response()->json([
            'message' => 'Vehicle models',
            'data' => VehiclePrerequisiteResource::collection($models)
        ]);
    }

    public function getTrims(VehicleTrimRequest $request)
    {
        $trims = $this->vehiclePrerequisiteService->trims($request->year, $request->make_id, $request->model_id);

        return response()->json([
            'message' => 'Vehicle trims',
            'data' => VehiclePrerequisiteResource::collection($trims)
        ]);
    }
}
