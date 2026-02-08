<?php

namespace App\Modules\Lead\Application\Services;

use App\Models\Lead;
use App\Modules\Lead\Domain\Contracts\LeadMetaRepositoryContract;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Modules\Lead\Infrastructure\Factories\LeadFactory;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;

class LeadMetaService
{

    public function __construct(
        protected LeadFactory $lead_factory
    ) {}

    public function addMeta(GenericId $leadId, array $data, LeadProductType $type)
    {
        $repo = $this->lead_factory->make($type);
        $repo->addLeadMeta($leadId, $data);
    }

    public function updateMeta(Lead $lead, array $data, LeadProductType $type)
    {
        $repo = $this->lead_factory->make($type);
        $repo->updateLeadMeta($lead, $data);
    }

    public function byCustomerId(GenericId $customerId, LeadProductType $type, array $map)
    {
        $repo = $this->lead_factory->make($type);
        return $repo->getLeadByCustomerId($customerId, $map);
    }

    public function byLeadId(Uuid $uuid, LeadProductType $type, array $map)
    {
        $repo = $this->lead_factory->make($type);
        return $repo->getLeadByLeadId($uuid, $map);
    }

    public function memberKeys(GenericId $leadId, LeadProductType $type)
    {
        $repo = $this->lead_factory->make($type);
        return $repo->getMemberKeys($leadId);
    }

    public function leadHealthMetaToArrayColumns(array $metas)
    {
        $members = [];

        foreach ($metas as $meta) {
            // The key looks like "health_member_name_1" or "health_member_dob_2"
            // Extract suffix number from key using regex
            if (preg_match('/health_member_(first_name|last_name|gender|relationship|dob)_(\d+)$/', $meta->key, $matches)) {
                $type = $matches[1]; // 'name' or 'dob'
                $index = $matches[2]; // e.g. '1', '2', '3' as string

                // Initialize if not set
                if (!isset($members[$index])) {
                    $members[$index] = [
                        'member_first_name' => null,
                        'member_last_name' => null,
                        'member_gender' => null,
                        'member_relationship' => null,
                        'member_dob' => null
                    ];
                }

                // Assign the value according to type
                if ($type === 'first_name') {
                    $members[$index]['member_first_name'] = $meta->value;
                } elseif ($type === 'last_name') {
                    $members[$index]['member_last_name'] = $meta->value;
                } elseif ($type === 'gender') {
                    $members[$index]['member_gender'] = $meta->value;
                } elseif ($type === 'relationship') {
                    $members[$index]['member_relationship'] = $meta->value;
                } elseif ($type === 'dob') {
                    $members[$index]['member_dob'] = $meta->value;
                }
            }
        }

        // Optional: re-index to numeric keys and get array values only
        $members = array_values($members);

        return $members;
    }
}
