<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | Roles
            |--------------------------------------------------------------------------
            */
            $roles = [
                'super_admin'   => 'Super Admin',
                'admin'         => 'Admin',
                'agent'         => 'Agent',
                'team_lead'     => 'Team Lead',
                'customer'      => 'Customer',
                'partner'       => 'Partner',
                'underwriter'   => 'Underwriter',
                'claims_officer' => 'Claims Officer',
                'finance'       => 'Finance',
                'support'       => 'Support',
            ];

            $roleIds = [];
            foreach ($roles as $slug => $name) {
                $roleIds[$slug] = DB::table('roles')->updateOrInsert(
                    ['slug' => $slug],
                    ['name' => $name]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Permissions
            |--------------------------------------------------------------------------
            */
            $permissions = [
                // Leads
                'leads.create',
                'leads.read',
                'leads.update',
                'leads.assign',

                // Customers
                'customers.read',

                // Quotes
                'quotes.create',
                'quotes.read',
                'quotes.adjust',

                // Policies
                'policies.read',
                'policies.create',
                'policies.approve',

                // Claims
                'claims.create',
                'claims.read',
                'claims.update',
                'claims.review',
                'claims.approve',

                // Payments & Finance
                'payments.create',
                'payments.read',
                'refund.process',

                // Discounts
                'discount.approve',

                // Reports
                'reports.read',
                'reports.financial',

                // Profile
                'profile.read',
            ];

            $permissionIds = [];
            foreach ($permissions as $permission) {
                $permissionIds[$permission] = DB::table('permissions')->updateOrInsert(
                    ['slug' => $permission],
                    ['name' => ucfirst(str_replace('.', ' ', $permission))]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Role → Permission Mapping
            |--------------------------------------------------------------------------
            */
            $rolePermissions = [

                // 1️⃣ Super Admin (ALL)
                'super_admin' => ['*'],

                // 2️⃣ Admin
                'admin' => [
                    'leads.create',
                    'leads.read',
                    'leads.update',
                    'customers.read',
                    'policies.read',
                    'policies.create',
                    'reports.read',
                ],

                // 3️⃣ Agent
                'agent' => [
                    'leads.read',
                    'leads.update',
                    'quotes.create',
                    'quotes.read',
                    'policies.read',
                ],

                // 4️⃣ Team Lead
                'team_lead' => [
                    'leads.read',
                    'leads.assign',
                    'discount.approve',
                    'reports.read',
                ],

                // 5️⃣ Customer
                'customer' => [
                    'profile.read',
                    'policies.read',
                    'payments.create',
                    'claims.create',
                ],

                // 6️⃣ Partner
                'partner' => [
                    'leads.create',
                    'leads.read',
                    'quotes.read',
                ],

                // 7️⃣ Underwriter
                'underwriter' => [
                    'policies.approve',
                    'quotes.adjust',
                    'claims.review',
                ],

                // 8️⃣ Claims Officer
                'claims_officer' => [
                    'claims.read',
                    'claims.update',
                    'claims.approve',
                ],

                // 9️⃣ Finance
                'finance' => [
                    'payments.read',
                    'refund.process',
                    'reports.financial',
                ],

                // 🔟 Support
                'support' => [
                    'customers.read',
                    'policies.read',
                    'claims.read',
                ],
            ];

            /*
            |--------------------------------------------------------------------------
            | Attach Permissions
            |--------------------------------------------------------------------------
            */
            foreach ($rolePermissions as $roleSlug => $perms) {

                $roleId = DB::table('roles')->where('slug', $roleSlug)->value('id');

                // Super Admin gets ALL permissions
                if (in_array('*', $perms)) {
                    $allPermissionIds = DB::table('permissions')->pluck('id');
                    foreach ($allPermissionIds as $pid) {
                        DB::table('role_permission')->updateOrInsert([
                            'role_id' => $roleId,
                            'permission_id' => $pid,
                        ]);
                    }
                    continue;
                }

                foreach ($perms as $permSlug) {
                    $permId = DB::table('permissions')->where('slug', $permSlug)->value('id');

                    DB::table('role_permission')->updateOrInsert([
                        'role_id' => $roleId,
                        'permission_id' => $permId,
                    ]);
                }
            }
        });
    }
}
