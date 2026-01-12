<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('document_types')->truncate();

        $documents = [

            /*
            |--------------------------------------------------------------------------
            | General / Customer Documents
            |--------------------------------------------------------------------------
            */
            [
                'module' => 'general',
                'name' => 'ID / Passport',
                'description' => 'Proof of identity',
                'required' => true,
            ],
            [
                'module' => 'general',
                'name' => 'Driver’s License',
                'description' => 'For vehicle or travel policies',
                'required' => true,
            ],
            [
                'module' => 'general',
                'name' => 'Address Proof',
                'description' => 'Utility bill or rental contract',
                'required' => true,
            ],
            [
                'module' => 'general',
                'name' => 'National ID',
                'description' => 'Legal verification',
                'required' => true,
            ],
            [
                'module' => 'general',
                'name' => 'Contact Verification',
                'description' => 'Email confirmation or phone verification',
                'required' => true,
            ],
            [
                'module' => 'general',
                'name' => 'Photo / Profile',
                'description' => 'Customer profile photo',
                'required' => false,
            ],
            [
                'module' => 'general',
                'name' => 'Signature',
                'description' => 'Agreements or contracts',
                'required' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | Vehicle Insurance Documents
            |--------------------------------------------------------------------------
            */
            [
                'module' => 'vehicle',
                'name' => 'Vehicle Registration (RC / Logbook)',
                'description' => 'Proof of vehicle ownership',
                'required' => true,
            ],
            [
                'module' => 'vehicle',
                'name' => 'Vehicle Insurance History',
                'description' => 'No-Claim Bonus (NCB) calculation',
                'required' => false,
            ],
            [
                'module' => 'vehicle',
                'name' => 'Vehicle Inspection Report',
                'description' => 'Pre-coverage condition verification',
                'required' => false,
            ],
            [
                'module' => 'vehicle',
                'name' => 'Driving License',
                'description' => 'Customer eligibility verification',
                'required' => true,
            ],
            [
                'module' => 'vehicle',
                'name' => 'Vehicle Photos',
                'description' => 'Vehicle condition verification',
                'required' => true,
            ],
            [
                'module' => 'vehicle',
                'name' => 'Previous Policy Document',
                'description' => 'Renewal or claim reference',
                'required' => false,
            ],

            /*
            |--------------------------------------------------------------------------
            | Health Insurance Documents
            |--------------------------------------------------------------------------
            */
            [
                'module' => 'health',
                'name' => 'Medical Records',
                'description' => 'Pre-existing conditions verification',
                'required' => true,
            ],
            [
                'module' => 'health',
                'name' => 'Lab Reports / Test Results',
                'description' => 'Risk assessment',
                'required' => false,
            ],
            [
                'module' => 'health',
                'name' => 'Prescription Records',
                'description' => 'Coverage details',
                'required' => false,
            ],
            [
                'module' => 'health',
                'name' => 'ID Proof',
                'description' => 'Customer verification',
                'required' => true,
            ],
            [
                'module' => 'health',
                'name' => 'Dependent Proof',
                'description' => 'Family coverage verification',
                'required' => false,
            ],
            [
                'module' => 'health',
                'name' => 'Previous Health Insurance Policy',
                'description' => 'Policy continuity',
                'required' => false,
            ],

            /*
            |--------------------------------------------------------------------------
            | Travel Insurance Documents
            |--------------------------------------------------------------------------
            */
            [
                'module' => 'travel',
                'name' => 'Passport',
                'description' => 'Travel eligibility verification',
                'required' => true,
            ],
            [
                'module' => 'travel',
                'name' => 'Visa',
                'description' => 'Destination verification',
                'required' => true,
            ],
            [
                'module' => 'travel',
                'name' => 'Flight Tickets / Itinerary',
                'description' => 'Coverage period verification',
                'required' => true,
            ],
            [
                'module' => 'travel',
                'name' => 'Travel History / Previous Travel Insurance',
                'description' => 'Risk assessment',
                'required' => false,
            ],
            [
                'module' => 'travel',
                'name' => 'ID Proof',
                'description' => 'Customer verification',
                'required' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | Pet Insurance Documents
            |--------------------------------------------------------------------------
            */
            [
                'module' => 'pet',
                'name' => 'Pet Registration / Microchip ID',
                'description' => 'Animal verification',
                'required' => true,
            ],
            [
                'module' => 'pet',
                'name' => 'Vaccination Certificate',
                'description' => 'Health verification',
                'required' => true,
            ],
            [
                'module' => 'pet',
                'name' => 'Vet Reports',
                'description' => 'Pre-existing conditions',
                'required' => false,
            ],
            [
                'module' => 'pet',
                'name' => 'Pet Photos',
                'description' => 'Pet identification',
                'required' => true,
            ],
            [
                'module' => 'pet',
                'name' => 'Owner Proof',
                'description' => 'Legal ownership verification',
                'required' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | Home Insurance Documents
            |--------------------------------------------------------------------------
            */
            [
                'module' => 'home',
                'name' => 'Property Ownership Proof',
                'description' => 'Legal ownership verification',
                'required' => true,
            ],
            [
                'module' => 'home',
                'name' => 'Property Valuation / Appraisal',
                'description' => 'Premium calculation',
                'required' => false,
            ],
            [
                'module' => 'home',
                'name' => 'Photos / Floor Plan',
                'description' => 'Property risk assessment',
                'required' => true,
            ],
            [
                'module' => 'home',
                'name' => 'Previous Policy Documents',
                'description' => 'Renewal and coverage continuity',
                'required' => false,
            ],
            [
                'module' => 'home',
                'name' => 'Identity Proof of Owner',
                'description' => 'Legal verification',
                'required' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | Policy & Claims Documents
            |--------------------------------------------------------------------------
            */
            [
                'module' => 'policy',
                'name' => 'Policy Document',
                'description' => 'Issued coverage proof',
                'required' => true,
            ],
            [
                'module' => 'policy',
                'name' => 'Quote Document',
                'description' => 'Pre-policy offer',
                'required' => true,
            ],
            [
                'module' => 'claims',
                'name' => 'Claim Form',
                'description' => 'Initiate claim process',
                'required' => true,
            ],
            [
                'module' => 'claims',
                'name' => 'Payment Receipt / Invoice',
                'description' => 'Proof of payment',
                'required' => true,
            ],
            [
                'module' => 'policy',
                'name' => 'Endorsement / Amendment Document',
                'description' => 'Policy modification',
                'required' => false,
            ],
            [
                'module' => 'claims',
                'name' => 'Claim Supporting Documents',
                'description' => 'Photos, invoices, hospital bills',
                'required' => true,
            ],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('document_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('document_types')->insert($documents);
    }
}
