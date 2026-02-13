<?php

namespace App\Shared\Infrastructure\Http\Controllers;

use App\Modules\Master\Application\Services\VehiclePrerequisiteService;
use App\Modules\Master\Infrastructure\Http\Requests\VehicleMakeRequest;
use App\Modules\Master\Infrastructure\Http\Requests\VehicleModelRequest;
use App\Modules\Master\Infrastructure\Http\Requests\VehicleTrimRequest;
use App\Shared\Application\Services\MasterService;
use App\Shared\Infrastructure\Http\Resources\VehiclePrerequisiteResource;
use App\Shared\Domain\Enums\PolicyType;
use App\Shared\Domain\Enums\SpecificationType;
use App\Shared\Domain\Enums\YesNo;
use App\Shared\Domain\Enums\ClaimHistory;
use App\Shared\Domain\Enums\Emirates;
use Illuminate\Support\Facades\Request;

class VehicleController
{
    public function __construct(
        protected VehiclePrerequisiteService $vehiclePrerequisiteService,
        protected MasterService $masterService
    ) {}

    public function leadVehicle(Request $request)
    {

        $countries = $this->masterService->countries();

        $years = array_map(
            fn($year) => [
                'label' => (string) $year,
                'value' => $year,
            ],
            range(2020, 2015)
        );

        return response()->json([
            'message' => 'Manage lead vehicle prerequisites',
            'data' => [
                'claim_histories' => ClaimHistory::toDropdownArray(),
                'policy_types' => PolicyType::toDropdownArray(),
                'specification_types' => SpecificationType::toDropdownArray(),
                'yes_no' => YesNo::toDropdownArray(),
                'emirates' => Emirates::toDropdownArray(),
                'countries' => $countries,
                'years' => $years
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
