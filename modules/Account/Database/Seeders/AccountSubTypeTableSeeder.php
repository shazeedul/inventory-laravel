<?php

namespace Modules\Account\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Account\Entities\AccountSubType;

class AccountSubTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        AccountSubType::insert([
            0 => [
                'code' => 1,
                'name' => 'None',
                'status' => true,
                'created_at' => now(),
            ],
            1 => [
                'code' => 2,
                'name' => 'Employee',
                'status' => true,
                'created_at' => now(),
            ],
            2 => [
                'code' => 3,
                'name' => 'Customer',
                'status' => true,
                'created_at' => now(),
            ],
            3 => [
                'code' => 4,
                'name' => 'Supplier',
                'status' => true,
                'created_at' => now(),
            ],
            4 => [
                'code' => 5,
                'name' => 'Borrower',
                'status' => true,
                'created_at' => now(),
            ]
        ]);
    }
}
