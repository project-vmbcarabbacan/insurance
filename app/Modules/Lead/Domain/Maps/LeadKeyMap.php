<?php

namespace App\Modules\Lead\Domain\Maps;

final class LeadKeyMap
{

    public static function VehicleLeadCustomerById()
    {
        return [
            "customer_id",
            "vehicle_make",
            "vehicle_make_id",
            "vehicle_model",
            "vehicle_model_id",
            "vehicle_trim",
            "vehicle_trim_id",
            "vehicle_year",
            "identifier_type",
            "plate_number",
            "engine_number",
            "vehicle_value",
            "lead_description"
        ];
    }

    /* views */
    public static function viewVehicleLeadById()
    {
        return [
            "lead_details",
            "vin",
            "plate_number",
            "vehicle_value",
            "vehicle_specification",
            "driver_full_name",
            "driver_dob",
            "driver_nationality_name",
            "driving_experience",
            "driver_license_number",
            "registration_emirate",
            "last_claim_history",
            "policy_type",
            "policy_expired",
        ];
    }

    public static function viewHealthLeadById()
    {
        return [
            "lead_details",
            "insurance_for",
            "emirates",
            "nationality_name",
            "existing_insurance",
            "has_medical_condition",
            "insure_to",
            "salary",
            "medical_conditions",
            "gender",
        ];
    }

    /* updates */

    public static function updateVehicleByLeadId()
    {
        return [
            "vehicle_make_id",
            "vehicle_year",
            "vehicle_model_id",
            "vehicle_trim_id",
            "vin",
            "plate_number",
            "engine_number",
            "vehicle_value",
            "vehicle_specification",
            "driver_first_name",
            "driver_last_name",
            "driver_dob",
            "driver_nationality",
            "driving_experience",
            "driver_license_number",
            "registration_emirate",
            "last_claim_history",
            "policy_type",
            "policy_expired",
        ];
    }

    public static function updateHealthByLeadId()
    {
        return [
            "insurance_for",
            "emirates",
            "nationality",
            "existing_insurance",
            "has_medical_condition",
            "insure_to",
            "salary",
            "medical_conditions",
            "gender",
        ];
    }

    /* active leads */
    public static function activeVehicleLead()
    {
        return [
            'customer_id',
            'vehicle_make_id',
            'vehicle_year',
            'vehicle_model_id',
            'vehicle_trim_id',
        ];
    }

    public static function activeHealthLead()
    {
        return [
            'customer_id',
            'insurance_for'
        ];
    }
}
