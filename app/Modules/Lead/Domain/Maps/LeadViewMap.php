<?php

namespace App\Modules\Lead\Domain\Maps;

final class LeadViewMap
{

    public static function VehicleView()
    {
        return [
            [
                "title" => "Vehicle Information",
                "type" => "fields",
                "fields" => [
                    ["key" => "product", "label" => "Product", "colSpan" => 4],
                    ["key" => "lead_details", "label" => "Vehicle Details", "colSpan" => 4],
                    ["key" => "status", "label" => "Status", "type" => "badge", "colSpan" => 4],
                    ["key" => "due_date", "label" => "Due Date", "colSpan" => 4],
                ],
            ],
            [
                "title" => "Vehicle Details",
                "type" => "fields",
                "fields" => [
                    ["key" => "vin", "label" => "VIN", "colSpan" => 4],
                    ["key" => "plate_number", "label" => "Plate Number", "colSpan" => 4],
                    ["key" => "vehicle_value", "label" => "Vehicle Value", "colSpan" => 4],
                    ["key" => "vehicle_specification", "label" => "Specification", "colSpan" => 4],
                    ["key" => "registration_emirate", "label" => "Registration Emirate", "colSpan" => 4],
                ],
            ],
            [
                "title" => "Driver Information",
                "type" => "fields",
                "fields" => [
                    ["key" => "driver_full_name", "label" => "Driver Full Name", "colSpan" => 4],
                    ["key" => "driver_dob", "label" => "Date of Birth", "colSpan" => 4],
                    ["key" => "driver_nationality", "label" => "Nationality", "colSpan" => 4],
                    ["key" => "driving_experience", "label" => "Driving Experience (Years)", "colSpan" => 4],
                    ["key" => "driver_license_number", "label" => "License Number", "colSpan" => 4],
                ],
            ],
            [
                "title" => "Policy Information",
                "type" => "fields",
                "fields" => [
                    ["key" => "policy_type", "label" => "Policy Type", "colSpan" => 4],
                    ["key" => "policy_expired", "label" => "Policy Expired", "colSpan" => 4],
                    ["key" => "last_claim_history", "label" => "Last Claim History", "colSpan" => 8],
                    ["key" => "agent_name", "label" => "Agent Name", "colSpan" => 4],
                ],
            ],
        ];
    }

    public static function HealthView()
    {
        return [
            [
                "title" => "Lead Information",
                "type" => "fields",
                "fields" => [
                    ["key" => "product", "label" => "Product", "colSpan" => 4],
                    ["key" => "lead_details", "label" => "Lead Details", "colSpan" => 4],
                    ["key" => "due_date", "label" => "Due Date", "colSpan" => 4],
                    ["key" => "status", "label" => "Status", "type" => "badge", "colSpan" => 4],
                    ["key" => "insurance_for", "label" => "Insurance For", "colSpan" => 4],
                    ["key" => "emirates", "label" => "Emirates", "colSpan" => 4],
                    ["key" => "nationality", "label" => "Nationality", "colSpan" => 4],
                    ["key" => "existing_insurance", "label" => "Existing Insurance", "colSpan" => 8],
                    ["key" => "has_medical_condition", "label" => "Medical Condition", "colSpan" => 4],
                ],
            ],
            [
                "title" => "Members",
                "type" => "array",
                "key" => "members",
            ]
        ];
    }
}
