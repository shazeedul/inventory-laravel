<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Permission\Entities\Permission;
use Modules\Role\Entities\Role;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // foreign key check disable
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // permission table truncate
        DB::table('permissions')->truncate();
        // role table truncate
        DB::table('roles')->truncate();
        // role permission table truncate
        DB::table('role_has_permissions')->truncate();
        // user permission table truncate
        DB::table('model_has_permissions')->truncate();
        // user role table truncate
        DB::table('model_has_roles')->truncate();
        // foreign key check enable
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $permissions = [
            'General' => [],
            'User' => [
                'user_management',
                'role_management',
                'permission_management',
                'sub_code_management',
                'account_predefine_management',
                'account_predefine_update',
                'coa_management',
            ],

            'Dashboard' => [
                'overall_count_report',
            ],
            'Setting' => [
                'setting_management',
                'mail_setting_management',
                'recaptcha_setting_management',
                'module_setting_management',
                'env_setting_management',
                'language_setting_management',
            ],
            'Backup' => [
                'backup_management',
            ],
            'Account' => [
                'account_management',
                'financial_year_management',
            ],
            'Supplier' => [
                'supplier_management',
            ],
            'Customer' => [
                'customer_management',
            ],
            'Product' => [
                'unit_management',
                'category_management',
                'product_management',
            ],
            'Purchase' => [
                'purchase_management',
            ],
            'Invoice' => [
                'invoice_management',
            ],
            'Stock' => [
                'stock_management',
            ],
        ];
        $roles = [
            'User' => [],
        ];

        $administrator = Role::create(['name' => 'Administrator']);
        foreach ($permissions as $group => $groups) {
            foreach ($groups as $permission) {
                Permission::create([
                    'name' => $permission,
                    'group' => $group,
                ])->assignRole($administrator);
            }
        }
        foreach ($roles as $role => $permissions) {
            $role = Role::create(['name' => $role]);
            $role->givePermissionTo($permissions);
        }
        $users = [
            [
                'name' => 'SYED SHAZEEDUL ISLAM',
                'email' => 'shazeedul.dev@gmail.com',
                'password' => Hash::make('shazeedul.dev1971#'),
                'email_verified_at' => now(),
                'status' => 'Active',
                'role' => 'Administrator',
            ], [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => Hash::make('user'),
                'email_verified_at' => now(),
                'status' => 'Active',
                'role' => 'User',
            ],
        ];
        foreach ($users as $userRaw) {
            //   find or create
            $user = User::firstOrCreate(
                [
                    'email' => $userRaw['email'],
                ],
                [
                    'name' => $userRaw['name'],
                    'password' => $userRaw['password'],
                    'email_verified_at' => $userRaw['email_verified_at'],
                    'status' => $userRaw['status'],
                ]
            );
            $user->assignRole($userRaw['role']);
        }
    }
}
