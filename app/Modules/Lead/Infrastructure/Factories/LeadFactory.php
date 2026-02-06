<?php

namespace App\Modules\Lead\Infrastructure\Factories;

use App\Modules\Lead\Domain\Contracts\LeadMetaRepositoryContract;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Modules\Lead\Infrastructure\repositories\VehicleLeadMetRepository;
// use App\Modules\Lead\Infrastructure\repositories\HealthLeadMetRepository;
// use App\Modules\Lead\Infrastructure\repositories\HomeLeadMetRepository;
// use App\Modules\Lead\Infrastructure\repositories\TravelLeadMetRepository;
// use App\Modules\Lead\Infrastructure\repositories\PetLeadMetRepository;

class LeadFactory
{
    public function __construct(
        private VehicleLeadMetRepository $vehicleRepository,
        // private HealthLeadMetaRepository $healthRepository,
        // private HomeLeadMetaRepository $homeRepository,
        // private TravelLeadMetaRepository $travelRepository,
        // private PetLeadMetaRepository $petRepository,
    ) {}

    public function make(LeadProductType $type): LeadMetaRepositoryContract
    {
        return match ($type) {
            LeadProductType::VEHICLE => $this->vehicleRepository,
            // LeadProductType::HEALTH => app(HealthLeadMetRepository::class),
            // LeadProductType::HOME => app(HomeLeadMetRepository::class),
            // LeadProductType::TRAVEL => app(TravelLeadMetRepository::class),
            // LeadProductType::PET => app(PetLeadMetRepository::class),
        };
    }
}
